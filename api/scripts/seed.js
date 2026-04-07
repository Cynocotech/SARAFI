import { db } from "../src/db.js";
import { runMigrations } from "./migrate-lib.js";

runMigrations();

const count = db.prepare("SELECT COUNT(*) AS total FROM exchange_offices").get().total;
if (count === 0) {
  const office = db
    .prepare(
      `INSERT INTO exchange_offices (name, city, status, identity_verified, about, payment_methods)
       VALUES (?, ?, ?, ?, ?, ?)`
    )
    .run(
      "IranIU Exchange",
      "London",
      "active",
      1,
      "Trusted exchange office for GBP, EUR and USD.",
      JSON.stringify(["cash", "visa", "mastercard"])
    );

  db.prepare(
    `INSERT INTO exchange_rates (exchange_office_id, from_currency, to_currency, buy_rate, sell_rate, margin)
     VALUES (?, ?, ?, ?, ?, ?)`
  ).run(office.lastInsertRowid, "GBP", "IRR", 85450, 85800, 0.4);
}

console.log("Seed complete.");
