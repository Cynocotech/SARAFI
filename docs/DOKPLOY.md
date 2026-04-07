# Deploy on Dokploy (React + Node)

This repository now deploys as two services:

- `frontend` (React SPA on `ex.iraniu.uk`)
- `api` (Node/Express on `api.ex.iraniu.uk`)

## 1. Create services in Dokploy

1. Open Dokploy -> Applications -> Create.
2. Use Docker Compose and point to `docker-compose.yml`.
3. Configure domains:
   - `frontend` service -> `ex.iraniu.uk`
   - `api` service -> `api.ex.iraniu.uk`

## 2. Environment variables

Use `docs/dokploy.env.example` as baseline.

Required values:

- `FRONTEND_URL=https://ex.iraniu.uk`
- `API_URL=https://api.ex.iraniu.uk`
- `CORS_ORIGIN=https://ex.iraniu.uk`

## 3. Persistent storage

Attach a volume to the API service:

- `/app/data` -> persists SQLite database (`exchange.sqlite`)

## 4. Health checks

- Frontend: `/`
- API: `/health`

## 5. First deploy

Run compose deploy and verify:

1. `GET https://api.ex.iraniu.uk/health` returns `{ "ok": true }`
2. `GET https://api.ex.iraniu.uk/api/exchanges` returns JSON list
3. `https://ex.iraniu.uk` renders React app

## 6. Cutover checklist

1. Deploy `api` first and validate `/health`.
2. Deploy `frontend` with `VITE_API_URL=https://api.ex.iraniu.uk`.
3. Update DNS:
   - `ex.iraniu.uk` -> frontend service
   - `api.ex.iraniu.uk` -> api service
4. Smoke test:
   - exchange list page
   - exchange details page
   - create/list rates through API
5. Disable old Laravel/PHP Dokploy app once traffic is stable.
