# UK Currency Exchange Directory – React + Node

This repository now runs as a React frontend and Node API backend.

## Stack

- React (Vite, React Router)
- Node.js (Express)
- SQLite (`better-sqlite3`)
- Docker Compose for local and Dokploy deployment

## Project structure

- `frontend/` React SPA (`ex.iraniu.uk`)
- `api/` Node API (`api.ex.iraniu.uk`)

## Local setup

```bash
npm install
npm run migrate:api
npm run seed:api
```

Run services separately:

```bash
npm run dev:api
npm run dev:frontend
```

Frontend defaults to `http://localhost:5173`, API to `http://localhost:4000`.

## API endpoints (migrated core)

- `GET /health`
- `GET /api/dashboard/stats`
- `GET /api/exchanges`
- `GET /api/exchanges/:id`
- `GET /api/exchanges/:id/click`
- `POST /api/exchanges/:id/contact`
- `GET /api/offices/:id/rates`
- `POST /api/offices/:id/rates`
- `PUT /api/rates/:id`
- `DELETE /api/rates/:id`
- `PUT /api/offices/:id/special-rate`
- `DELETE /api/offices/:id/special-rate`
- `PUT /api/offices/:id/payment-methods`
- `PUT /api/offices/:id/transfer-fee`
- `POST /api/stripe/webhook` (placeholder)

## Dokploy

Use `docker-compose.yml` with two services (`frontend`, `api`).

Full guide: `docs/DOKPLOY.md`  
Env template: `docs/dokploy.env.example`
