# Installation

The Order module ships as part of the Microweber core. Nothing to `composer require`. This page documents what it pulls in, the schema it owns, and the configuration knobs that change its behaviour.

---

## Service provider

`Modules\Order\Providers\OrderServiceProvider` is registered automatically via `module.json`. It:

- Binds the `order_manager` singleton in the container — resolves to `Modules\Order\Repositories\OrderManager`.
- Binds the `order_repository` singleton — resolves to `Modules\Order\Repositories\OrderRepository`.
- Binds `OrderManagerContract` → `OrderManager` so callers can type-hint the interface.
- Registers the `OrderResource` on the admin Filament panel.
- Loads `routes/api.php` and `routes/admin.php`.

The `OrderManagerContract` interface pins the public surface (place_order, save, export_orders, update_quantities, get_by_id, get, get_items, order_items, delete_order). If you write a custom OrderManager implementation, type-hint the contract — the binding picks it up automatically.

---

## Route loading

| File | Mount point | Loaded by |
|---|---|---|
| `routes/api.php` | `/api/order/*` (deprecated REST) + `/api/module/orders/*` (headless) | `OrderServiceProvider::boot()` |
| `routes/admin.php` | `/admin/order/export` | `OrderServiceProvider::boot()` |

`/api/module/orders/*` routes carry `throttle:public` middleware for the GET endpoints and `scope:orders:write` for POST/PUT/DELETE so anonymous reads are rate-limited and writes require a token.

---

## Required configuration

Order reads two website options via `Option::getValue()`:

| Option key | Group | Default | Effect |
|---|---|---|---|
| `order_email_send_when` | website | `order_received` | `order_received` → admin email fires when Order row is created. `order_paid` → email fires when `OrderWasPaid` event lands. |
| `new_order_mail_template` | orders | (template id) | Mail template id `NewOrderNotification` looks up to render the body. |

Set from admin **Settings → Orders** or programmatically:

```php
\MicroweberPackages\Option\Models\Option::setValue('order_email_send_when', 'order_paid', 'website');
```

The Order module also reads — but does not own — these e-commerce options (covered in [Checkout installation](/modules/checkout/installation)):

- `order_email_enabled` (orders) — if `0`, `Checkout::confirmEmailSend()` returns early. Disabling this *also* disables `NewOrderNotification` since both check the same flag downstream of `NewOrderNotificationTrait`.
- `currency` (payments) — default currency code stored on new orders.

---

## Database

Order owns four tables. Run migrations during the normal Microweber install — no Order-specific migration step.

### `cart_orders` (44 columns)

Created by `2020_00_00_0000012_create_orders_table.php`. Subsequent migrations add columns:

- `2026_03_21_*` — index on `order_reference_id` (high-traffic lookup column)
- `2026_03_30_000001_*` — adds `user_id` (links to authenticated buyer)
- `2026_03_30_000002_*` — backfills `user_id` from legacy `created_by`
- `2026_04_02_000002_*` — adds `shipping_tracking_number` and `shipping_tracking_url`

Notable columns:

| Column | Type | Notes |
|---|---|---|
| `id` | bigint pk | |
| `order_reference_id` | string (indexed) | `ORDER-<YmdHis>-<4 random digits>`; uniqueness is application-checked, not unique-constrained — collisions trigger re-roll in `OrderService::place_order()` |
| `order_status` | string, default `new` | one of the `OrderStatus` enum values |
| `order_completed` | int, default 0 | `1` once payment is acknowledged or pay-on-delivery confirmed |
| `is_paid` | int, default 0 | `1` once payment is verified |
| `amount` | float | total |
| `currency` / `currency_code` | string | redundant — Filament uses `currency_code` for filters |
| `customer_id`, `user_id` | bigint nullable | guest orders have both `null` |
| `payment_provider`, `payment_provider_id`, `payment_status`, `payment_data` | string/int/json | denormalized for fast reads |
| `shipping_provider`, `shipping_provider_id`, `shipping_amount`, `shipping_tracking_number`, `shipping_tracking_url` | string/int/float/string/string | denormalized |
| `taxes_amount`, `discount_value`, `discount_type`, `coupon_id`, `promo_code` | float/int/string/int/string | denormalized totals snapshot at place_order time |
| `deleted_at` | timestamp nullable | soft-delete enabled |

### `order_status_history`

Created by `2026_04_02_000001_*`. One row per status transition. Columns: `id`, `order_id`, `old_status`, `new_status`, `user_id` (which admin), `note`, `created_at`. Auto-populated by `Order::updating()` boot hook whenever `order_status` is dirty.

### `order_refunds`

Created by `2026_04_02_000003_*`. Columns: `id`, `order_id`, `payment_id`, `amount` (decimal 10,2), `type` (`full` / `partial`), `reason`, `note`, `status` (`completed` / `pending` / `failed`), `refunded_by` (admin user id), `created_at`.

### `order_cancel_reasons`

Created by `2026_03_05_000001_*`. Columns: `id`, `user_id`, `order_id`, `stripe_session_id`, `reason`, `ip_address`, `created_at`. Indexed on `(user_id, order_id)`. Optional — only populated when a cancellation includes a reason.

---

## Dependencies on other modules

| Module | Why Order needs it |
|---|---|
| **[Cart](/modules/cart/)** | `place_order` reads the session cart via `cart_manager->get_cart()`, links cart rows to the new `order_id`. |
| **[Checkout](/modules/checkout/)** | `OrderService::place_order` calls `app->checkout_manager->mark_order_as_paid()` and `->after_checkout()` post-create. |
| **Product** | `OrderFilter` joins cart rows → content rows for product/category search filters. Filament item-repeater shows product names. |
| **Customer** | Order `BelongsTo` Customer; Filament form has a customer select. |
| **Payment** | Order `MorphMany` Payment; `PaymentsRelationManager` shows the payment audit on the Filament order detail page. `PaymentListener` listens for Payment events and reacts when `rel_type === Order::class`. |
| **Shipping** | `shipping_amount` denormalized on the Order row at place_order time. Shipping label / tracking flows live in the Shipping module. |
| **MailTemplate** | `NewOrderNotification` looks up the template id from the `new_order_mail_template` option, renders via the project's Twig pipeline. |
| **User/Admin** | `OrderCreatedListener` enumerates admin users to push the database notification + email. `OrderStatusHistory.user_id` records which admin changed status. |

If any of these modules is disabled, Order's surface remains functional but degrades:
- Cart disabled → `place_order` cannot find session cart → throws `OrderException`.
- Payment disabled → `payments` morph relationship empty → Filament panel shows "No payments" but order survives.
- MailTemplate disabled → notification skips email channel, database notification still lands.

---

## Sanity check after install

```bash
# Admin Filament page loads
curl -I http://your-site/admin/order

# Headless API (rate-limited public)
curl http://your-site/api/module/orders | jq '.data | length'

# Order model resolves
php artisan tinker --execute='echo \Modules\Order\Models\Order::count();'

# OrderManager singleton resolves
php artisan tinker --execute='echo get_class(app("order_manager"));'
# → Modules\Order\Repositories\OrderManager
```

If `/admin/order` returns 404, confirm the `OrderResource` is registered on the admin panel — check the providers list and the `OrderServiceProvider::boot()` panel hook.
