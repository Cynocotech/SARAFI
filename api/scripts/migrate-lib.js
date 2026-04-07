import { db } from "../src/db.js";

export function runMigrations() {
  db.exec(`
    CREATE TABLE IF NOT EXISTS exchange_offices (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      tagline TEXT,
      about TEXT,
      city TEXT,
      postcode TEXT,
      status TEXT NOT NULL DEFAULT 'draft',
      identity_verified INTEGER NOT NULL DEFAULT 0,
      clicks INTEGER NOT NULL DEFAULT 0,
      special_rate_buy REAL,
      special_rate_sell REAL,
      payment_methods TEXT,
      transfer_fee_under_amount REAL,
      transfer_fee_amount REAL,
      created_at TEXT NOT NULL DEFAULT (datetime('now')),
      updated_at TEXT NOT NULL DEFAULT (datetime('now'))
    );
  `);

  db.exec(`
    CREATE TABLE IF NOT EXISTS exchange_rates (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      exchange_office_id INTEGER NOT NULL,
      from_currency TEXT NOT NULL,
      to_currency TEXT NOT NULL,
      buy_rate REAL NOT NULL,
      sell_rate REAL NOT NULL,
      margin REAL,
      created_at TEXT NOT NULL DEFAULT (datetime('now')),
      updated_at TEXT NOT NULL DEFAULT (datetime('now')),
      FOREIGN KEY(exchange_office_id) REFERENCES exchange_offices(id) ON DELETE CASCADE
    );
  `);

  db.exec(`
    CREATE TABLE IF NOT EXISTS exchange_contacts (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      exchange_office_id INTEGER NOT NULL,
      name TEXT NOT NULL,
      email TEXT,
      message TEXT NOT NULL,
      created_at TEXT NOT NULL DEFAULT (datetime('now')),
      FOREIGN KEY(exchange_office_id) REFERENCES exchange_offices(id) ON DELETE CASCADE
    );
  `);
}
