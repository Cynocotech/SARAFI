import { db } from "./db.js";

function parseMethods(value) {
  if (!value) return [];
  try {
    return JSON.parse(value);
  } catch {
    return [];
  }
}

export function registerRoutes(app) {
  app.get("/api/dashboard/stats", (_req, res) => {
    const offices = db.prepare("SELECT COUNT(*) AS total FROM exchange_offices").get().total;
    const rates = db.prepare("SELECT COUNT(*) AS total FROM exchange_rates").get().total;
    const active = db
      .prepare("SELECT COUNT(*) AS total FROM exchange_offices WHERE status = 'active'")
      .get().total;
    res.json({ offices, rates, active });
  });

  app.get("/api/exchanges", (_req, res) => {
    const offices = db
      .prepare(
        `SELECT id, name, city, status, identity_verified, clicks, special_rate_buy, special_rate_sell
         FROM exchange_offices
         ORDER BY clicks DESC, id DESC`
      )
      .all();
    res.json(offices);
  });

  app.get("/api/exchanges/:id", (req, res) => {
    const office = db.prepare("SELECT * FROM exchange_offices WHERE id = ?").get(req.params.id);
    if (!office) return res.status(404).json({ error: "Exchange office not found" });
    office.payment_methods = parseMethods(office.payment_methods);
    res.json(office);
  });

  app.get("/api/exchanges/:id/click", (req, res) => {
    db.prepare("UPDATE exchange_offices SET clicks = clicks + 1, updated_at = datetime('now') WHERE id = ?").run(req.params.id);
    res.json({ ok: true });
  });

  app.post("/api/exchanges/:id/contact", (req, res) => {
    const { name, email, message } = req.body || {};
    if (!name || !message) return res.status(422).json({ error: "name and message are required" });
    db.prepare(
      `INSERT INTO exchange_contacts (exchange_office_id, name, email, message)
       VALUES (?, ?, ?, ?)`
    ).run(req.params.id, name, email || null, message);
    res.status(201).json({ ok: true });
  });

  app.get("/api/offices/:id/rates", (req, res) => {
    const rows = db
      .prepare(
        `SELECT id, exchange_office_id, from_currency, to_currency, buy_rate, sell_rate, margin
         FROM exchange_rates WHERE exchange_office_id = ?
         ORDER BY id DESC`
      )
      .all(req.params.id);
    res.json(rows);
  });

  app.post("/api/offices/:id/rates", (req, res) => {
    const { from_currency, to_currency, buy_rate, sell_rate, margin } = req.body || {};
    if (!from_currency || !to_currency || buy_rate == null || sell_rate == null) {
      return res.status(422).json({ error: "from_currency, to_currency, buy_rate, sell_rate are required" });
    }

    const result = db
      .prepare(
        `INSERT INTO exchange_rates
         (exchange_office_id, from_currency, to_currency, buy_rate, sell_rate, margin)
         VALUES (?, ?, ?, ?, ?, ?)`
      )
      .run(req.params.id, from_currency, to_currency, buy_rate, sell_rate, margin ?? null);
    const rate = db.prepare("SELECT * FROM exchange_rates WHERE id = ?").get(result.lastInsertRowid);
    res.status(201).json(rate);
  });

  app.put("/api/rates/:id", (req, res) => {
    const { buy_rate, sell_rate, margin } = req.body || {};
    db.prepare(
      `UPDATE exchange_rates
       SET buy_rate = COALESCE(?, buy_rate),
           sell_rate = COALESCE(?, sell_rate),
           margin = COALESCE(?, margin),
           updated_at = datetime('now')
       WHERE id = ?`
    ).run(buy_rate ?? null, sell_rate ?? null, margin ?? null, req.params.id);
    const rate = db.prepare("SELECT * FROM exchange_rates WHERE id = ?").get(req.params.id);
    if (!rate) return res.status(404).json({ error: "Rate not found" });
    res.json(rate);
  });

  app.delete("/api/rates/:id", (req, res) => {
    db.prepare("DELETE FROM exchange_rates WHERE id = ?").run(req.params.id);
    res.status(204).send();
  });

  app.put("/api/offices/:id/special-rate", (req, res) => {
    const { special_rate_buy, special_rate_sell } = req.body || {};
    db.prepare(
      `UPDATE exchange_offices
       SET special_rate_buy = ?, special_rate_sell = ?, updated_at = datetime('now')
       WHERE id = ?`
    ).run(special_rate_buy ?? null, special_rate_sell ?? null, req.params.id);
    res.json({ ok: true });
  });

  app.delete("/api/offices/:id/special-rate", (req, res) => {
    db.prepare(
      `UPDATE exchange_offices
       SET special_rate_buy = NULL, special_rate_sell = NULL, updated_at = datetime('now')
       WHERE id = ?`
    ).run(req.params.id);
    res.json({ ok: true });
  });

  app.put("/api/offices/:id/payment-methods", (req, res) => {
    const paymentMethods = Array.isArray(req.body?.payment_methods) ? req.body.payment_methods : [];
    db.prepare(
      `UPDATE exchange_offices SET payment_methods = ?, updated_at = datetime('now') WHERE id = ?`
    ).run(JSON.stringify(paymentMethods), req.params.id);
    res.json({ ok: true });
  });

  app.put("/api/offices/:id/transfer-fee", (req, res) => {
    const { transfer_fee_under_amount, transfer_fee_amount } = req.body || {};
    db.prepare(
      `UPDATE exchange_offices
       SET transfer_fee_under_amount = ?, transfer_fee_amount = ?, updated_at = datetime('now')
       WHERE id = ?`
    ).run(transfer_fee_under_amount ?? null, transfer_fee_amount ?? null, req.params.id);
    res.json({ ok: true });
  });

  app.post("/api/stripe/webhook", (_req, res) => {
    // Placeholder for migrated Stripe webhook logic.
    res.json({ received: true });
  });
}
