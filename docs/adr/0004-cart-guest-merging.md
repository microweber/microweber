# ADR-0004 — Cart guest-merging strategy

> **Cycle-111 / AI-124 / TICKET-CS (2026-05-09)**
> Status: Accepted
> Context: how anonymous guest carts merge with authenticated user
> carts on login / register.

---

## Context

A visitor adds items to their cart while anonymous (no account).
They then log in (or register). The cart system has three
inputs to reconcile:

1. **Session cart** — items added during the anonymous session,
   keyed in the DB by `cart.session_id` (the Laravel session id
   string).
2. **Cookie cart (legacy)** — pre-cycle-22 the cart used a
   `MW_CART` cookie. Some old installs / browser fingerprints
   may still carry one. We honour it as a read-only fallback.
3. **Database cart** — items previously added by this user
   account from a different device / session, keyed by
   `cart.user_id`.

The decision: when do we merge, replace, or partition these?

---

## Decision

### Source of truth: session cart wins on login

When a user logs in:

1. The current session's `cart.session_id` rows are **moved** to
   the user's account (set `cart.user_id` to the user; keep the
   session_id so the carryover works across the login redirect).
2. Any prior DB rows for this `cart.user_id` from previous
   sessions are **merged** by `(user_id, rel_type, rel_id)`:
   - If a product is in both, the QUANTITIES are SUMMED (not
     replaced). This matches the user's intuition: "I had 2 in
     my last session and added 3 today; now I have 5."
   - If a product is only in one, it stays.
3. The cookie cart (if present) is read once during login and
   merged via the same `(user_id, rel_type, rel_id)` quantity-
   sum rule.

### Source of truth: session cart wins on logout

When a user logs out:

1. The `cart.user_id` is cleared on the session's rows.
2. The session_id stays, so the user can continue browsing as
   anonymous and the cart remains accessible until the session
   expires.
3. On the NEXT login (whether to a different account or back to
   the same one), the merge rule above re-runs.

### Source of truth: session cart wins on session expiry

When the session expires (Laravel garbage-collects the session):

1. The session's cart rows persist in the DB (we don't delete
   them on session GC).
2. They become orphans (no session_id binding, no user_id).
3. A daily `mw:prune-orphans` artisan command (AI-119 / TICKET-BJ)
   removes orphan cart rows older than 30 days.

Why 30 days: long-tail abandoned-cart recovery. Marketing flows
sometimes email "you left items in your cart!" up to 14 days
after; 30 days is a 2× safety margin.

### Cookie carts are READ-only after cycle-22

The `MW_CART` cookie was the pre-Laravel cart store. Cycle-22
migrated to the DB-backed model. The cookie path:

- READ on login → merge into DB.
- NEVER WRITTEN by post-cycle-22 code.
- Cleared on first successful merge so old carts don't keep
  re-applying.

A future cycle should drop the read path entirely once telemetry
shows zero MW_CART cookies in the wild for 90 days.

---

## Consequences

- **Positive**: users never lose a cart on login. The QTY-sum
  rule matches the natural "I had X, I added Y, I have X+Y"
  intuition.
- **Positive**: anonymous-to-authenticated transition is a single
  DB transaction (move-and-merge) — no cart-replay UI required.
- **Positive**: orphan cleanup is bounded (30-day prune).
- **Negative**: a user who DELIBERATELY wanted to start fresh
  on login has to clear their cart manually. We rely on the
  cart UI's "Empty cart" button being discoverable.
- **Negative**: the cookie-read path (item 3) is technical debt.
  Every additional cycle without the post-cycle-22 telemetry
  delays its removal.

---

## Related ADRs

- ADR-0001..0003 — Security model (cart inputs are user data,
  ADR-0001 + ADR-0002 + ADR-0003 apply)

---

## Related cycles

- Cycle-22: Migrated cart from cookie to DB
- Cycle-43: Cart price recalculation hardened
- Cycle-72 (AI-72 / TICKET-AC): Cart Add-to-Cart de-dup +
  cycle-102 / AI-107 compound index
- Cycle-86 (AI-71 / TICKET-AA): Cart price-display rounding
- Cycle-88 (AI-76 / TICKET-CV): Customer-email resolver for
  admin-on-behalf-of cart flows
