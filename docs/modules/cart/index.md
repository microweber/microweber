# Cart Module

The Cart module is the **state manager** of the e-commerce sub-cluster. It sits between [Product](/modules/product/) (the data owner) and Checkout (the conversion flow), holding the customer's in-progress selection, calculating totals, applying coupons, and bridging guest sessions into authenticated accounts on login.

> **TL;DR** — Cart is a single line-item table (`cart`) where every row represents one product/variant the shopper has selected, scoped by `session_id`. Helpers like `update_cart()` and `cart_total()` are the canonical entry points; under the hood, the [CartService](./api.md#cartservice) + [CartTotalsService](./api.md#carttotalsservice) + [CartCouponService](./api.md#cartcouponservice) trio does the actual work.

---

## What this module owns

| Concern | Storage / surface |
|---|---|
| In-progress line items | `cart` table — one row per item, scoped by `session_id` |
| Cart lifecycle | `order_completed` column (0 = active, 1 = converted/abandoned) |
| Item attributes | `custom_fields_data` (base64) + `custom_fields_json` (variant choices) |
| Totals (subtotal, tax, shipping, discounts, total) | Computed on-demand by `CartTotalsService` — never stored |
| Coupon application + rate-limited consumption | `CartCouponService` |
| Guest → user cart merge on login | `UserLoginListener` |
| Public REST API | `/api/module/cart/*` |
| Legacy POST endpoints | `/api/update_cart`, `/api/empty_cart`, etc. |
| `/cart` storefront page | `CartPageController` + Livewire `CartItems` component |
| Server-canonical pricing | Always resolves price from Product, ignoring client-submitted values |

What this module does **NOT** own:

- Product schema, variant SKUs, stock counts → [Product module](/modules/product/)
- Coupon definitions + redemption tracking → Coupons module (Cart only *applies* + *consumes*)
- Tax rules → Tax module (Cart calls into it via `CartTotalsService::getTax()`)
- Shipping rate calculation → Shipping module
- Currency formatting + conversion → Currency module
- Order persistence → Order module (Checkout's `place_order` converts cart → order)
- Payment processing → Payment module

---

## Architectural fact: single-table, line-item-per-row

Microweber's cart has **no separate cart-container row**. Every row in `cart` is one line item. There is no `cart.id = 7; cart_items.cart_id = 7` two-table split. This is intentional and has consequences:

- Operations like "empty the cart" are `DELETE FROM cart WHERE session_id = ?`, not "delete one cart row".
- `session_id` is the de-facto cart identifier; it survives login via the `UserLoginListener` which rewrites `session_id` from the old guest session to the authenticated session.
- The `Cart` model represents **one line item**, not the whole cart. Don't expect `$cart->items` — there is no such relation. Use `Cart::queryCartItems($sessionId)` to retrieve the whole cart.
- `MAX_ITEMS_PER_SESSION = 500` is a hard cap that protects against session-stuffing DoS attacks.

If you're new to this layout, read [Usage](./usage.md) before writing any custom code.

---

## Server-canonical pricing — the security contract

The Cart module **never trusts client-submitted prices**. When you call `update_cart(['content_id' => 42, 'qty' => 2, 'price' => 999.00])`, the `price` field is **ignored** for the canonical insert. `CartService::updateCart()` resolves the canonical price from the Product (base price + variant override + applicable [pricing rules](/modules/product/usage.md#pricing-rules)) and stores that.

Why this matters:

- A malicious frontend cannot manipulate prices via tampered `<input>` values.
- Pricing rule changes between page load and add-to-cart are picked up automatically.
- The stored `price` column reflects what was charged at the moment of cart insertion — useful for audit + abandoned-cart recovery.

The corollary: **any pricing logic must live in the Product module's `AdvancedPricingService`** — never in Cart. Cart just reads.

---

## Custom fields — variant + option storage

When a product has variants ("Size: M + Color: Red") or options ("Engraving text: 'Happy Birthday'"), those choices are stored on the cart line in two formats:

| Column | Format | Purpose |
|---|---|---|
| `custom_fields_data` | base64-encoded PHP serialised | Legacy format; some templates read this |
| `custom_fields_json` | JSON | Modern format; preferred for new code |

Both are written on every save — they're equivalent. Read either, but write through `CartService::updateCart()` so both columns stay in sync.

Importantly: **the same `content_id` with different custom-field values is a different line item.** Two `update_cart()` calls with `content_id=42` but different `custom_fields_json` produce two separate rows, not a quantity bump on one row. Same content + same custom fields → quantity merges.

---

## Cart lifecycle

```
   guest session
      │
      ▼
  ┌─────────┐    update_cart()   ┌──────────────┐
  │ empty   │ ─────────────────► │ active items │
  └─────────┘                    └──────────────┘
      │                                │
      │     login / merge              │ remove_cart_item()
      │     UserLoginListener          │ update_item_qty(qty=0)
      ▼                                ▼
  ┌──────────────┐               ┌──────────────┐
  │ active items │               │   removed    │
  │ (user-bound) │               │ (soft delete)│
  └──────────────┘               └──────────────┘
      │
      │  Checkout::place_order
      ▼
  ┌────────────────────────┐
  │ order_completed = 1    │   ◄── Order module takes ownership
  │ order_id = <new order> │
  └────────────────────────┘
```

States are not enums — they're column-flag combinations:

- **Active:** `order_completed = 0`, `order_id IS NULL`.
- **Converted:** `order_completed = 1`, `order_id IS NOT NULL`. Stays in the `cart` table forever as historical record.
- **Abandoned:** `order_completed = 0`, no checkout for N hours. No background job auto-marks these — abandonment is a query-side concept (`SELECT … WHERE created_at < now() - INTERVAL N HOUR`).
- **Recoverable:** A converted cart can be re-injected into a new session via `mw_shop_recover_shopping_cart($orderId)` — see [Usage](./usage.md#abandoned-cart-recovery).

---

## Quick start

### Add a product to the cart

```php
update_cart([
    'content_id' => 42,
    'qty'        => 2,
]);
```

That's it. The helper:

1. Resolves the canonical price from Product (ignoring any submitted `price`).
2. Checks stock availability via `CartService::isProductInStock()`.
3. Reserves stock via Product's `InventoryService::reserve()` ([P0 critical](./troubleshooting.md#out-of-stock-but-product-shows-as-in-stock)).
4. Inserts or upserts the `cart` row keyed on `(session_id, rel_id, custom_fields_data)`.
5. Dispatches `AddToCartEvent` for subscribers.
6. Returns the response array with totals + warnings.

### Add a variant

```php
update_cart([
    'content_id'    => 42,
    'qty'           => 1,
    'custom_fields' => [
        'size'  => 'M',
        'color' => 'Red',
    ],
]);
```

The variant matrix is resolved via Product's `ProductVariantCombination::findByAttributes($productId, $attributes)` — see the [variant API](/modules/product/api.md#productvariantcombination).

### Get the current cart

```php
$items = get_cart();           // all items for this session
$total = cart_total();         // grand total (subtotal + tax + shipping − discount)
$count = cart_get_items_count(); // SUM(qty)
$totals = cart_totals();       // full breakdown: subtotal, tax, shipping, discount, total
```

### Apply a coupon

```php
$result = app('cart_coupon_service')->applyCoupon(
    couponCode: 'SUMMER25',
    customerEmail: auth()->user()?->email,
    customerIp: request()->ip(),
);

if ($result['success']) {
    // Coupon stored in session; subsequent cart_total() reflects the discount.
}
```

### Remove an item

```php
remove_cart_item(['id' => $cartItemId]);
```

### Empty the cart

```php
empty_cart();
```

---

## Where this module fits in the e-commerce cluster

Cart sits in the **middle** of the e-commerce flow. Most of its lifecycle is two-way:

```
                Shop  (orchestrator)
                  │
       ┌──────────┼──────────────────────┐
       │          │                      │
   Product   ─►  Cart  ─►  Checkout  ─►  Order  ─►  Payment
   (you-       (state    (conversion)    (final)   (gateway)
    here)      manager)
                  ▲          │              │
                  │          │              │
              Coupons,    Shipping,      Inventory commit
              Currency,    Tax           (back to Product)
              Tax
```

Cross-module call patterns:

- **Cart → Product** — reads price, stock, variant data; calls `InventoryService::reserve()` on every add.
- **Cart → Coupons** — validates + consumes via the Coupons module's discount engine.
- **Cart → Tax** — `CartTotalsService::getTax()` calls into the Tax engine with `{cart_items, customer_location}`.
- **Cart → Shipping** — Checkout queries shipping rates and stuffs them back into the cart totals call.
- **Checkout → Cart** — reads items + totals when constructing the Order.
- **Order ← Cart** — the conversion sets `order_completed=1` and writes `order_id` back on every cart row.

For bug routing across these modules, the [Shop "Where to file bugs" matrix](/modules/shop/troubleshooting.md#where-to-file-bugs) is canonical. Cart-specific routing rules live in [./troubleshooting.md#where-to-file-bugs](./troubleshooting.md#where-to-file-bugs).

---

## Files in this section

- [Installation](./installation.md) — composer/module manifest, migrations, helper auto-load, environment config.
- [Usage](./usage.md) — day-to-day patterns: adding, updating, removing, coupons, totals, persistence, mini-cart.
- [API Reference](./api.md) — every public class, method, helper, event, and endpoint.
- [Examples](./examples.md) — full recipes (custom mini-cart, coupon webhook, abandoned-cart email, cross-sells, headless cart).
- [Troubleshooting](./troubleshooting.md) — common issues + their fixes.
