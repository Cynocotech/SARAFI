# Deploy on Dokploy (Docker + SQLite)

This app ships with a production-ready **Dockerfile** (PHP 8.3-FPM + Nginx + Supervisor + queue worker + scheduler loop). It is designed to run on Dokploy as a single app service.

## 1. Create application in Dokploy

1. Open **Dokploy** → **Applications** → **Create**.
2. Choose **Git** (recommended) or upload/build context.
3. **Build type**: Dockerfile (default path `Dockerfile` at repo root).
4. **Port**: container exposes **80**. Attach your domain/reverse proxy in Dokploy.

## 2. Environment variables

Add these in the app **Environment** tab (adjust values):

| Variable | Example | Notes |
|----------|---------|--------|
| `APP_NAME` | `AghasSarafi` | |
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | |
| `APP_KEY` | `base64:...` | Generate: `php artisan key:generate --show` |
| `APP_URL` | `https://your-domain.com` | Must match public URL (HTTPS) |
| `DB_CONNECTION` | `sqlite` | Default and recommended |
| `DB_DATABASE` | `/var/www/html/database/database.sqlite` | Persist this path with a volume |
| `SESSION_DRIVER` | `database` | Already default in `.env.example` |
| `CACHE_STORE` | `database` | |
| `QUEUE_CONNECTION` | `database` | Queue worker runs inside the container |
| `RUN_MIGRATIONS` | `true` | Keep `true` for single replica deployments |

Optional: Stripe keys, mail settings, etc. (see `.env.example`).

You can copy from `docs/dokploy.env.example` for a Dokploy-ready baseline (`APP_URL` and `SESSION_DOMAIN` are already set for `ex.iraniu.uk`).

## 3. Persistent storage (required)

Attach Dokploy volumes:

- `/var/www/html/database` (SQLite file persistence)
- `/var/www/html/storage/app` (uploaded files such as logos)

Without the database volume, data will reset on redeploy.

## 4. First deploy

On startup, the container entrypoint:

1. ensures `database.sqlite` exists,
2. runs `php artisan storage:link`,
3. runs `php artisan migrate --force` (when `RUN_MIGRATIONS=true`),
4. caches config/routes/views/events (when `APP_ENV=production`),
5. starts Nginx + PHP-FPM + queue worker + scheduler.

## 5. Docker Compose on Dokploy (optional)

Alternatively use **Docker Compose** in Dokploy:

- Point compose to `docker-compose.yml` in the repo.
- Set `APP_KEY` and any optional secrets in Dokploy env.
- Expose the `app` service port **80**.
- Keep the same two persistent volume mounts used above.

## 6. Health check

Laravel registers **`GET /up`** (see `bootstrap/app.php`). In Dokploy you can set the health check path to `/up`.

## 7. Scaling note

If you run multiple replicas, each replica will run queue and scheduler. For most Laravel apps, use **1 replica** unless you intentionally design for multi-replica workers/scheduling.

---

## Local test

```bash
cp .env.example .env
# Set APP_KEY and APP_URL
docker compose up --build
```

Open `http://localhost:8080` (see `docker-compose.yml` ports).
