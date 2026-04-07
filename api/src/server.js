import { createApp } from "./app.js";
import { config } from "./config.js";
import { runMigrations } from "../scripts/migrate-lib.js";

runMigrations();

const app = createApp();
app.listen(config.port, () => {
  console.log(`API listening on :${config.port}`);
});
