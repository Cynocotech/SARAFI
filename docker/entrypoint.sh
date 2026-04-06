#!/bin/sh
set -e
cd /var/www/html

# ── 1. Ensure SQLite file exists and is writable ─────────────────────────────
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
  DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  mkdir -p "$(dirname "$DB_FILE")"
  touch "$DB_FILE"
  chown www-data:www-data "$DB_FILE"
  chmod 664 "$DB_FILE"
  echo "[entrypoint] SQLite database: $DB_FILE"
fi

# ── 2. Storage symlink ───────────────────────────────────────────────────────
php artisan storage:link 2>/dev/null || true

# ── 3. Migrations ────────────────────────────────────────────────────────────
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
