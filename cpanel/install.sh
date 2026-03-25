#!/usr/bin/env bash
# Laravel / cPanel helper — run over SSH from the project root (same folder as artisan).
# Usage:
#   bash cpanel/install.sh
# Or after upload:
#   cd ~/path/to/exchange-backend && bash cpanel/install.sh

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
cd "$ROOT"

echo "=============================================="
echo "  Laravel installer (cPanel / shared hosting)"
echo "  App root: ${ROOT}"
echo "=============================================="
echo ""

if ! command -v php >/dev/null 2>&1; then
  echo "ERROR: php not found in PATH. Enable PHP CLI in cPanel or use full path, e.g. /usr/local/bin/php"
  exit 1
fi

PHP_VER="$(php -r 'echo PHP_VERSION;')"
echo "PHP version: ${PHP_VER}"
php -r 'exit(version_compare(PHP_VERSION, "8.2.0", "<") ? 1 : 0);' || {
  echo "ERROR: PHP 8.2+ required."
  exit 1
}

if [[ ! -f artisan ]]; then
  echo "ERROR: artisan not found. Run this script from your Laravel project (parent of cpanel/)."
  exit 1
fi

if [[ ! -f .env.example ]]; then
  echo "ERROR: .env.example missing."
  exit 1
fi

if [[ ! -f .env ]]; then
  echo "Creating .env from .env.example ..."
  cp .env.example .env
else
  echo ".env already exists — values will be updated (database + production defaults)."
fi

echo ""
echo "--- Application ---"
read -r -p "APP_URL (e.g. https://yourdomain.com): " APP_URL
APP_URL="${APP_URL:-http://localhost}"
read -r -p "APP_NAME [Laravel]: " APP_NAME
APP_NAME="${APP_NAME:-Laravel}"

echo ""
echo "--- MySQL (create database + user in cPanel → MySQL Databases first) ---"
read -r -p "DB_HOST [127.0.0.1]: " DB_HOST
DB_HOST="${DB_HOST:-127.0.0.1}"
read -r -p "DB_PORT [3306]: " DB_PORT
DB_PORT="${DB_PORT:-3306}"
read -r -p "DB_DATABASE: " DB_DATABASE
if [[ -z "${DB_DATABASE}" ]]; then
  echo "ERROR: DB_DATABASE is required."
  exit 1
fi
read -r -p "DB_USERNAME: " DB_USERNAME
if [[ -z "${DB_USERNAME}" ]]; then
  echo "ERROR: DB_USERNAME is required."
  exit 1
fi
read -r -s -p "DB_PASSWORD: " DB_PASSWORD
echo ""
if [[ -z "${DB_PASSWORD}" ]]; then
  echo "WARNING: empty DB_PASSWORD (unusual for shared hosting)."
fi

TMPDIR="$(mktemp -d)"
trap 'rm -rf "${TMPDIR}"' EXIT
JSON_FILE="${TMPDIR}/config.json"
PASS_FILE="${TMPDIR}/dbpass"
printf '%s' "${DB_PASSWORD}" > "${PASS_FILE}"
chmod 600 "${PASS_FILE}"

export I_APP_NAME="${APP_NAME}"
export I_APP_URL="${APP_URL}"
export I_DB_HOST="${DB_HOST}"
export I_DB_PORT="${DB_PORT}"
export I_DB_DATABASE="${DB_DATABASE}"
export I_DB_USERNAME="${DB_USERNAME}"
export I_JSON="${JSON_FILE}"

php -r '
$j = [
  "APP_NAME" => getenv("I_APP_NAME"),
  "APP_ENV" => "production",
  "APP_DEBUG" => "false",
  "APP_URL" => getenv("I_APP_URL"),
  "DB_CONNECTION" => "mysql",
  "DB_HOST" => getenv("I_DB_HOST"),
  "DB_PORT" => getenv("I_DB_PORT"),
  "DB_DATABASE" => getenv("I_DB_DATABASE"),
  "DB_USERNAME" => getenv("I_DB_USERNAME"),
];
file_put_contents(getenv("I_JSON"), json_encode($j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
'

php "${SCRIPT_DIR}/set-env.php" "${JSON_FILE}" "--db-password-file=${PASS_FILE}"

echo ""
echo "--- APP_KEY ---"
if grep -qE '^APP_KEY=.{10,}' .env 2>/dev/null; then
  echo "APP_KEY already set."
else
  php artisan key:generate --force --no-interaction
fi

echo ""
echo "--- Composer (vendor) ---"
if [[ ! -d vendor ]]; then
  if command -v composer >/dev/null 2>&1; then
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
  else
    echo "WARNING: composer not found and vendor/ missing."
    echo "  Upload vendor/ from your PC (composer install --no-dev) or run: php composer.phar install ..."
    exit 1
  fi
else
  echo "vendor/ exists — skipping composer install."
fi

echo ""
echo "--- Permissions ---"
mkdir -p storage/framework/{sessions,views,cache/data} storage/logs bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

echo ""
echo "--- Database migrate ---"
php artisan migrate --force --no-interaction

echo ""
echo "--- Storage link ---"
php artisan storage:link --force 2>/dev/null || php artisan storage:link 2>/dev/null || true

echo ""
echo "--- Optimize (production) ---"
php artisan optimize 2>/dev/null || true

echo ""
echo "=============================================="
echo "  Done."
echo "  cPanel: set Document Root to .../public"
echo "  See: cpanel/README.md"
echo "=============================================="
