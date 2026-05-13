# Cart Module — Installation

The Cart module ships with Microweber. On a fresh install it's registered, migrated, and active out of the box. This page covers verification, migration history, helper auto-load, and the few knobs you may want to tune.

---

## Prerequisites

- Microweber 2.x running on Laravel 11.
- The [Product module](/modules/product/) **must** be enabled — Cart calls into `InventoryService` and product price resolution on every add.
- A session driver that survives long enough to be useful as a cart identifier. Defaults (`file` driver, 120-minute lifetime) work fine; if you've configured aggressive session GC, expect carts to vanish for guests.
- MySQL/MariaDB or PostgreSQL.

---

## Verify the module is registered

```bash
php artisan module:list | grep -i cart
```

Expected:

```
Cart   |   Enabled   |   Order 0   |   modules/cart
```

If missing:

```bash
php artisan module:enable Cart
```

`module.json`:

```json
{
  "name":      "Cart",
  "alias":     "cart",
  "providers": ["Modules\\Cart\\Providers\\CartServiceProvider"],
  "files":     ["Support/helpers.php"]
}
```

The `files` entry is important — it auto-loads `update_cart()`, `cart_total()`, etc. on every request. If your composer autoloader doesn't pick this up, run `composer dump-autoload`.

---

## Migrations

```bash
php artisan migrate
```

Cart ships three migrations:

| Migration | Adds |
|---|---|
| `2024_11_20_000001_create_cart_table.php` | Initial `cart` table schema |
| `2026_03_23_000001_add_indexes_to_cart.php` | `session_id`, `order_id`, `rel_id` single-column indexes |
| `2026_05_09_000001_add_ai_107_compound_indexes_to_cart.php` | Compound indexes: `(session_id, order_completed)`, `(session_id, is_active)`, `(rel_type, rel_id)` |

If you're upgrading from a pre-2026-03 build, the compound indexes are **mandatory** for production performance — every `get_cart()` call scans by `session_id + order_completed`, and missing the compound index turns that into a full table scan as soon as `cart` grows past a few thousand rows.

To re-apply only the Cart migrations:

```bash
php artisan migrate --force --path=Modules/Cart/database/migrations
```

---

## Service bindings

`CartServiceProvider::register()` binds singletons under the keys you'll see throughout the docs:

| Container key | Class | Used as |
|---|---|---|
| `cart_repository` | `CartRepository` | Per-request caching layer over the `cart` table |
| `cart_service` | `CartService` | Add/remove/update operations |
| `cart_totals_service` | `CartTotalsService` | Totals, tax, shipping |
| `cart_coupon_service` | `CartCouponService` | Coupon validation + apply + consume |
| `cart_manager` | `CartManager` | Backward-compat facade aggregating all four |
| `CartManagerContract` | → `cart_manager` | Type-hintable interface for DI |

Pull any of these via `app('cart_service')` or constructor injection.

---

## Helper functions

The auto-loaded `Support/helpers.php` exposes the global functions that templates + legacy code use:

| Helper | Maps to |
|---|---|
| `get_cart($params)` | `CartManager::get_cart()` |
| `update_cart($data)` | `CartManager::update_cart()` |
| `empty_cart()` | `CartManager::empty_cart()` |
| `cart_sum($returnAmount = true)` | `CartManager::sum()` |
| `cart_get_items_count()` | `CartManager::sum(false)` |
| `cart_total()` | `CartManager::total()` |
| `cart_totals($return = 'all')` | `CartManager::totals()` |
| `cart_get_tax()` | `CartManager::get_tax()` |
| `cart_get_discount()` | `CartManager::get_discount()` |
| `cart_get_discount_text()` | `CartManager::get_discount_text()` |
| `update_cart_item_qty($data)` | `CartManager::update_item_qty()` |
| `remove_cart_item($data)` | `CartManager::remove_item()` |
| `mw_shop_recover_shopping_cart($orderId)` | `CartManager::recover_cart()` |

These are global functions — you can call them from any blade template, Livewire component, controller, or job. The signatures are stable across Microweber versions.

---

## Configuration

The Cart module has no dedicated config file. The few tunables live in code:

### `MAX_ITEMS_PER_SESSION` (DoS hard cap)

`Modules/Cart/Models/Cart.php`:

```php
public const MAX_ITEMS_PER_SESSION = 500;
```

This caps how many distinct items one session can hold. Bump it if you run B2B installs with very large orders, but don't remove the cap — it's a DoS protection against session-stuffing.

### Session lifetime

Microweber's cart identifier is `session_id`. If `config/session.php`'s `lifetime` is short (e.g. 30 minutes), guest carts evaporate quickly. For storefronts where shoppers are mostly guests, bump this to several hours:

```php
// config/session.php
'lifetime' => 480,  // 8 hours
```

### Coupon rate limiting

`CartCouponService::applyCoupon()` enforces per-IP + per-email rate limits to prevent coupon-bruteforce. The defaults live in the Coupons module — see its docs for tuning. From Cart's side, the rate-limit context is built automatically:

```php
$context = [
    'cart_items'        => [...],          // resolved cart line items
    'product_ids'       => [42, 43, ...],   // distinct product IDs in cart
    'category_ids'      => [...],
    'user_id'           => auth()->id(),
    'customer_group_id' => $user?->customer_group_id,
];
```

### Inventory reservation window

The cart reserves stock for every line item. The duration is owned by the [Product module's InventoryService](/modules/product/installation.md#reservation-window) — defaults to 30 minutes. Without the `inventory:cleanup-reservations` scheduled task, abandoned carts will hold stock indefinitely.

> **CRITICAL** — make sure that scheduled task is running. See the [Product installation guide](/modules/product/installation.md#schedule-the-inventory-cleanup-job).

---

## Filament admin pages

The Cart module currently registers **one** Filament settings page:

- **CartAddModuleSettings** — Live Edit module settings for the `shop/cart_add` block. Lets admins configure the "Add to cart" button text and template per page.

There is no Filament Resource for cart inventory inspection — admins inspect carts via the Order module's "Abandoned carts" report (or via custom SQL during incident response).

---

## Optional: live-edit "Add to cart" module

The Cart module ships a [Live Edit](/modules/live-edit/) block that drops an "Add to cart" button onto any page. Configure via:

- Live Edit toolbar → Add module → Shop → Add to cart.
- Per-block settings: button text, template selection (default / bootstrap / shop_inner / mw_default).

The block reads the current page's content (when on a product page) or accepts a `for_id` attribute targeting any product.

---

## Verify the install

Run the Cart module's test suite:

```bash
./vendor/bin/phpunit --filter "Modules\\\\Cart"
```

Or just the headline test:

```bash
./vendor/bin/phpunit Modules/Cart/Tests/Unit/CartTest.php
```

The suite covers:

- `CartTest` — service-layer end-to-end add/remove/update.
- `CartModelTest` — model queries + stock validation.
- `CartRepositoryTest` — caching + aggregation.
- `CartTotalsServiceTest` — totals, tax, discount math.
- `CartCouponServiceTest` — coupon apply/validate.
- `CartCouponRateLimitTest` — rate-limit enforcement.
- `CartApiControllerTest` — REST API integration.
- `UserCartTest` — `UserCartScope` filter behaviour.

Green = wired correctly.

---

## Where to next

- [Usage](./usage.md) — programmatic patterns.
- [API Reference](./api.md) — every class + method.
- [Product module](/modules/product/) — the upstream data owner.
- [Shop module](/modules/shop/) — the orchestrator that puts Cart on the page.
