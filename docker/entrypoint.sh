#!/bin/sh
set -e
cd /var/www/html

# ── 1. Wait for MySQL ────────────────────────────────────────────────────────
if [ -n "$DB_HOST" ] && [ "${DB_CONNECTION:-mysql}" != "sqlite" ]; then
  echo "[entrypoint] Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
  i=0
  while [ "$i" -lt 60 ]; do
    if php -r "
      try {
        new PDO(
          'mysql:host='.getenv('DB_HOST').';port='.(getenv('DB_PORT')?:'3306'),
          getenv('DB_USERNAME')?:'root',
          getenv('DB_PASSWORD')?:'',
          [PDO::ATTR_TIMEOUT => 2]
        );
        exit(0);
      } catch (Throwable \$e) { exit(1); }
    " 2>/dev/null; then
      echo "[entrypoint] Database is reachable."
      break
    fi
    i=$((i + 1))
    sleep 2
  done
fi

# ── 2. Storage symlink ───────────────────────────────────────────────────────
php artisan storage:link 2>/dev/null || true

# ── 3. Migrations ────────────────────────────────────────────────────────────
# Set RUN_MIGRATIONS=false on replica containers (only one container should migrate).
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  echo "[entrypoint] Running migrations..."
  php artisan migrate --force --no-interaction
fi

# ── 4. Optimise for production ───────────────────────────────────────────────
if [ "${APP_ENV:-production}" = "production" ]; then
  echo "[entrypoint] Optimising..."
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan event:cache
fi

# ── 5. Hand off to supervisor ────────────────────────────────────────────────
echo "[entrypoint] Starting supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
