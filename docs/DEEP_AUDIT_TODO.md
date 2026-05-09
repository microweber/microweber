# Deep Audit — Outstanding Items

> **Cycle-113 / AI-119 / TICKET-BF + TICKET-BI + TICKET-BJ
> (2026-05-09)** — accumulating list of audit findings that are
> known but deferred. Each entry has a clear scope + effort
> estimate so a future cycle can pick them up without re-deriving.

---

## TICKET-BF — chunkById() backfill convention

When backfilling existing rows after a schema change, use
`chunkById()` (NOT `chunk()`) to avoid the off-by-one row-skip
that plain `chunk()` exhibits when modifying the rows it iterates.

Examples in-codebase:
- `cycle-43` re-encrypt sweep — uses `chunkById(500, 'id')`.
- `cycle-102` add-index migrations — no backfill needed (DDL only).

Convention going forward:

```php
// CORRECT
User::where('encrypted', false)
    ->chunkById(500, function ($users) {
        foreach ($users as $user) {
            $user->update(['email' => encrypt($user->email)]);
        }
    });

// WRONG — `chunk()` skips rows because the chunk window shifts
// as rows leave the original WHERE.
User::where('encrypted', false)
    ->chunk(500, function ($users) { ... });
```

Pin in tests: any new backfill migration must use `chunkById`,
not `chunk`. (No automated grep-gate yet — manual review during PR.)

---

## TICKET-BI — Foreign-key + onDelete audit

Microweber's tables have inconsistent FK enforcement — some
tables declare `->references()->on()->onDelete()`, others rely
on application-level cascades, others have no cascade at all.

Findings from `grep -rln "->references" Modules/*/database/migrations` +
inspection (cycle-113):

### Tables WITH foreign keys + onDelete

- `Modules/Cart/database/migrations/2024_11_20_000001_create_cart_table.php` —
  no FK on `cart.user_id` / `cart.order_id` (intentional: orphan-prune
  via daily cron, not cascade).
- `Modules/Newsletter/database/migrations/2024_02_28_164214_create_newsletter_subscribers_lists_table.php` —
  `subscribers.list_id` references `newsletter_lists.id` ON DELETE
  SET NULL.
- `Modules/Order/database/migrations/...` — `orders_data.order_id`
  references `orders.id` ON DELETE CASCADE.

### Tables WITHOUT foreign keys (relies on app-level cleanup)

- `content_fields.content_id` — orphans cleaned via `ContentManager::deleteWithFields()`.
- `content_data.content_id` — same.
- `categories_items.parent_id` / `.rel_id` — polymorphic, FK not
  expressible.
- `cms_settings` — singleton-style; no relations.

### Recommendation

For the polymorphic `*_items` and `*_data` tables, FKs aren't
expressible (the rel_type column determines which table the
rel_id points to). The `mw:prune-orphans` artisan command
(AI-119 / TICKET-BJ) is the canonical cleanup path.

For non-polymorphic relations that don't currently have FKs,
add them in a follow-up cycle when convenient — but verify the
SET NULL / CASCADE / RESTRICT choice matches the app-level
behaviour first.

---

## TICKET-BJ — Extend cycle-43 encrypt sweep

Cycle-43 added per-request encrypt-at-rest for
`users.password_history`. The brief asks to extend the same
treatment to:

1. **`payment_methods` (Stripe customer/card tokens)** —
   currently stored plaintext-on-DB; the Stripe token is itself
   a reference, so disclosure is medium-risk (attacker who reads
   the DB still needs Stripe's API key to charge), but
   defense-in-depth says encrypt anyway.

2. **`users.api_token`** — currently bcrypt-hashed (which is the
   right choice for an API token; encrypt would be a downgrade).
   **Action: skip — already correctly bcrypt-hashed.**

3. **`cms_settings` secret rows** — settings keys matching
   `*_secret`, `*_password`, `*_token`, `*_key` patterns. Already
   partially covered by the legacy `mw_settings_encrypt_keys`
   list; needs alignment with the cycle-43 model.

### Phase 1 (this cycle): document scope

Track the work here. Phase 2 (separate cycle) ships the encrypt
extension + the `mw:reencrypt-payment-methods` /
`mw:reencrypt-cms-settings` artisan commands referenced in
SETUP.md's app-key rotation procedure.

---

## See also

- SETUP.md — app-key rotation runbook (depends on the encrypt
  extension above being complete).
- ADR-0001 / 0002 — security principles (encrypt at rest is
  ADR-0002 fail-closed for sensitive columns).
