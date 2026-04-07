import path from "node:path";

const defaultDbPath = path.resolve(process.cwd(), "data", "exchange.sqlite");

export const config = {
  port: Number(process.env.PORT || 4000),
  corsOrigin: process.env.CORS_ORIGIN || "http://localhost:5173",
  databasePath: process.env.DATABASE_PATH || defaultDbPath,
  nodeEnv: process.env.NODE_ENV || "development",
};
