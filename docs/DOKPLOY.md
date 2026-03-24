# Deploy on Dokploy (Docker + MySQL)

This app ships with a **Dockerfile** (PHP 8.3-FPM + Nginx + Supervisor + queue worker) and a sample **`docker-compose.yml`** with MySQL 8.

## 1. Database (MySQL)

Create a MySQL database and user (Dokploy **Database** resource, or compose `mysql` service, or external managed MySQL). Note:

- Hostname (e.g. `mysql` if same compose network, or the hostname Dokploy shows)
- Port (usually `3306`)
- Database name, username, password

## 2. New application in Dokploy

1. Open **Dokploy** → **Applications** → **Create**.
2. Choose **Git** (recommended) or upload/build context.
3. **Build type**: Dockerfile (default path `Dockerfile` at repo root).
4. **Port**: container exposes **80** → map to your public port / domain (Dokploy usually sets reverse proxy to the container).

## 3. Environment variables

Add these in the app **Environment** tab (adjust values):

| Variable | Example | Notes |
|----------|---------|--------|
| `APP_NAME` | `AghasSarafi` | |
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | |
| `APP_KEY` | `base64:...` | Generate: `php artisan key:generate --show` |
| `APP_URL` | `https://your-domain.com` | Must match public URL (HTTPS) |
| `DB_CONNECTION` | `mysql` | |
| `DB_HOST` | `mysql` or host from Dokploy DB | |
| `DB_PORT` | `3306` | |
| `DB_DATABASE` | your DB name | |
| `DB_USERNAME` | your DB user | |
| `DB_PASSWORD` | your DB password | |
| `SESSION_DRIVER` | `database` | Already default in `.env.example` |
| `CACHE_STORE` | `database` | |
| `QUEUE_CONNECTION` | `database` | Queue worker runs inside the container |
| `RUN_MIGRATIONS` | `true` | Set `false` on extra replicas after first deploy |

Optional: Stripe keys, mail settings, etc. (see `.env.example`).

## 4. Persistent storage (uploads / `storage/app`)

The image is stateless. For **logos and file uploads**, attach a **volume** in Dokploy to:

`/var/www/html/storage/app`

(Keep `storage/logs` ephemeral or add another volume if you need log retention.)

## 5. First deploy

- Ensure the database is empty or run `php artisan migrate:fresh` only if you intend to wipe data.
- The container **entrypoint** waits for MySQL, runs `php artisan migrate --force`, `storage:link`, and `php artisan optimize` when `APP_ENV=production`.

## 6. Docker Compose on Dokploy

Alternatively use **Docker Compose** in Dokploy:

- Point compose to `docker-compose.yml` in the repo.
- Set `APP_KEY` and DB passwords in Dokploy env or an env file.
- Expose the `app` service port **80**.

## 7. Health check

Laravel registers **`GET /up`** (see `bootstrap/app.php`). In Dokploy you can set the health check path to `/up`.

## 8. Scheduler (optional)

This image does not run `schedule:work`. If you use scheduled tasks, add a **cron** or a second Dokploy service:

`php /var/www/html/artisan schedule:work`

---

**Local test**

```bash
cp .env.example .env
# Set APP_KEY, DB_* or use compose defaults
docker compose up --build
```

Open `http://localhost:8080` (see `docker-compose.yml` ports).
