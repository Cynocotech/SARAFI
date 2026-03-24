#!/bin/sh
set -e
cd /var/www/html

if [ -n "$DB_HOST" ] && [ "${DB_CONNECTION:-mysql}" != "sqlite" ]; then
  echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
  i=0
  while [ "$i" -lt 60 ]; do
    if php -r "
      try {
        \$pdo = new PDO(
          'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'),
          getenv('DB_USERNAME') ?: 'root',
          getenv('DB_PASSWORD') ?: '',
          [PDO::ATTR_TIMEOUT => 2]
        );
        exit(0);
      } catch (Throwable \$e) {
        exit(1);
      }
    " 2>/dev/null; then
      echo "Database is reachable."
      break
    fi
    i=$((i + 1))
    sleep 2
  done
fi

php artisan package:discover --ansi 2>/dev/null || true

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force --no-interaction
fi

php artisan storage:link 2>/dev/null || true

if [ "$APP_ENV" = "production" ]; then
  php artisan optimize
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
