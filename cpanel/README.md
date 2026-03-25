# cPanel / shared hosting (no Docker)

This folder is **not** a second copy of Laravel. Keep your project as one tree: upload the **whole repository**, then use these helpers over **SSH** (cPanel → **Terminal** or your host’s SSH).

## Before you run the installer

1. In **cPanel → MySQL® Databases**, create:
   - a database  
   - a MySQL user  
   - assign the user to the database with **ALL PRIVILEGES**  
2. Note **hostname** (often `localhost`, sometimes `127.0.0.1` or a socket host shown by the host).
3. **Document root** must point at Laravel’s `public` folder, for example:
   - `public_html/yourapp/public`  
   (not the project root.)

4. PHP **8.2+** with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `json`, `ctype`, `fileinfo`, `xml`, `gd` or `zip` (as required by your host).

5. **Composer:** either install Composer on the server, upload a `vendor/` directory built locally (`composer install --no-dev`), or use `php composer.phar` if you upload the phar.

## Run the interactive installer (SSH)

From the **Laravel root** (where `artisan` lives):

```bash
bash cpanel/install.sh
```

You will be prompted for:

- `APP_URL` (your real HTTPS URL)  
- `APP_NAME` (optional)  
- MySQL: `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

The script will:

- create `.env` from `.env.example` if missing  
- merge DB settings safely (passwords with special characters are OK)  
- generate `APP_KEY` if needed  
- run `composer install` if `composer` exists and `vendor/` is missing  
- `php artisan migrate --force`  
- `php artisan storage:link`  
- `php artisan optimize`

## Without SSH

- Upload the full project (including `vendor` if you cannot run Composer on the server).  
- Copy `.env.example` to `.env` in **File Manager**, edit DB variables and `APP_URL`, then in **Terminal** (if available) run only:

  ```bash
  php artisan key:generate --force
  php artisan migrate --force
  php artisan storage:link
  php artisan optimize
  ```

If your host has **no** SSH at all, use their PHP **cron** or a one-off “Run PHP” tool if offered, or ask the host to run the artisan commands.

## Files here

| File | Role |
|------|------|
| `install.sh` | Interactive prompts + artisan steps |
| `set-env.php` | Merges keys into `.env` with safe quoting |

## Security

- Do not commit real `.env` to Git.  
- After install, set `APP_DEBUG=false` and `APP_ENV=production` (the script sets these when you use `install.sh`).
