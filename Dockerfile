# syntax=docker/dockerfile:1
# ── Stage 1: build frontend assets ──────────────────────────────────────────
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci --prefer-offline
COPY . .
RUN npm run build

# ── Stage 2: production PHP image ───────────────────────────────────────────
FROM php:8.3-fpm-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        git \
        unzip \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_sqlite \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php-opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php.ini         /usr/local/etc/php/conf.d/app.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# Copy application source
COPY . .

# Bring in compiled frontend assets from stage 1
COPY --from=frontend /app/public/build ./public/build

# Finalise composer
RUN composer dump-autoload --optimize --classmap-authoritative

# Web server & process manager config
COPY docker/nginx.conf        /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/sites-enabled/default.bak 2>/dev/null || true

COPY docker/supervisord.conf  /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh     /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    # Create SQLite database file with correct ownership
    && mkdir -p database storage/framework/{sessions,views,cache/data} storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data database storage bootstrap/cache public/build \
    && chmod -R ug+rwx database storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
