# API Reference

Class, method, route, model, and event signatures for the Order module.

---

## OrderService

`Modules\Order\Services\OrderService` — the only sanctioned path that creates an Order. Bind via `app('order_manager')` (which is `OrderManager`, a thin proxy) or `app(\Modules\Order\Services\OrderService::class)`.

### `place_order(array $place_order): int`

Creates a new order inside a DB transaction. Returns the new `order_id`.

Pipeline:

1. Sanitizes XSS from string inputs (`strip_tags` + `xss_clean`).
2. Validates the session cart is non-empty (throws `OrderException` if empty).
3. Fires `OrderIsCreating` event with the attribute array.
4. Inserts the `cart_orders` row.
5. Links every cart row matching the supplied `session_id` to the new `order_id` (UPDATE cart SET order_id=? WHERE session_id=?).
6. `Order::created()` boot hook fires `OrderWasCreated`.
7. If `is_paid === 1` → fires `OrderWasPaid` + `checkout_manager->mark_order_as_paid($order_id)`.
8. `checkout_manager->after_checkout($order)` runs (inventory deduction listeners etc.).

Accepts the 26-key fillable array (see [Order model](#order-model)) plus `session_id` (required for cart linkage).

### `save(array $params): int`

Updates an existing order. Coerces `is_paid` string→int, JSON-encodes `payment_data` if it's an array, clears the OrderRepository caches. Returns the order id.

Direct `Order::save()` works too — but `OrderService::save()` is preferred for non-trivial updates because it also clears the analytics cache.

### `export_orders(array $params = []): string`

Returns a CSV of orders matching the filter. Default filter: `is_completed = 1`. Passing `['order_id' => 12345]` exports just that one order.

### `update_quantities(int $order_id): void`

Walks the cart items linked to the order and decrements stock on the corresponding Product rows. Called by `place_order` when `is_paid === 0` (pay-on-delivery flow — deduct now even though payment lags); the `is_paid === 1` path defers stock deduction to the `OrderWasPaid` listener in Product so the deduction happens exactly once whether the order pays sync or async.

---

## OrderManager (facade)

`Modules\Order\Repositories\OrderManager`. Container-bound as `order_manager`. Implements `OrderManagerContract`.

```php
app('order_manager')->place_order($data);             // → OrderService::place_order
app('order_manager')->save($params);                  // → OrderService::save
app('order_manager')->export_orders($params);         // → OrderService::export_orders
app('order_manager')->update_quantities($orderId);    // → OrderService::update_quantities
app('order_manager')->get_by_id($id);                 // → Order::getById
app('order_manager')->get($params);                   // → Order::getOrders
app('order_manager')->get_items($orderId);            // → Order::getOrderItems
app('order_manager')->order_items($orderId);          // alias for get_items
app('order_manager')->delete_order($id);              // → Order::deleteOrder (soft)
```

If you need to swap the implementation (custom OrderManager for a marketplace flow), bind your class to `OrderManagerContract` in a service provider — the facade resolves through the contract.

---

## OrderRepository

`Modules\Order\Repositories\OrderRepository`. Bound as `order_repository`. Extends `CachingModelRepository` and adds caching to `OrderStatsService` calls.

Public methods delegate to `OrderStatsService` (with cache wrapping):

```php
app('order_repository')->getOrdersTotalSumForPeriod(['from' => '2026-01-01']);
app('order_repository')->getOrdersCountForPeriod(['status' => 'completed']);
app('order_repository')->getBestSellingProductsForPeriod(['from' => '2026-01-01', 'limit' => 10]);
app('order_repository')->getBestSellingCategoriesForPeriod($params);
app('order_repository')->getOrderItemsCountForPeriod($params);
app('order_repository')->getOrdersCountGroupedByDate($params);
```

Cache TTL: 60s per stat call. Invalidated when `OrderService::save()` runs.

---

## OrderStatsService

`Modules\Order\Services\OrderStatsService`. Used directly only if you want to bypass caching (e.g. real-time admin widget).

Same method names as `OrderRepository` above. `$params` keys: `from` (Y-m-d), `to`, `currency`, `is_paid`, `status`, `customer_id`.

---

## Order model

`Modules\Order\Models\Order` — table `cart_orders`.

### Fillable (26 fields)

```
email, first_name, last_name, country, city, state, zip, address, address2,
other_info, phone, custom_fields_data, order_status, order_completed, is_paid,
amount, currency, currency_code, customer_id, payment_provider_id,
payment_provider, payment_status, shipping_provider_id, shipping_provider,
shipping_amount, shipping_tracking_number, shipping_tracking_url,
discount_value, taxes_amount, order_reference_id, invoice_id, promo_code,
skip_promo_code, coupon_id, discount_type, user_id
```

### Casts

| Column | Cast |
|---|---|
| `payment_data` | `array` (JSON in DB) |
| `custom_fields_data` | `array` |

### Accessors (computed properties)

| Accessor | Returns |
|---|---|
| `shippingMethodName()` | resolves `shipping_provider_id` → ShippingProvider.title |
| `addressText()` | concatenated address1, address2, city, state, zip, country |
| `paymentMethodName()` | resolves `payment_provider_id` → PaymentProvider.title |
| `customerName()` | `first_name . ' ' . last_name` (trimmed) |
| `thumbnail()` | first cart item's product thumbnail URL |
| `cartProducts()` | linked cart rows joined to content |

### Relationships

```php
$order->statusHistory  // HasMany OrderStatusHistory (created_at desc)
$order->refunds        // HasMany OrderRefund
$order->payments       // MorphMany Payment (rel_type = Order::class)
$order->customer       // BelongsTo Customer
$order->user           // HasOne User (by user_id)
$order->cart           // HasCartItems trait → cart rows where order_id = this.id
```

### Boot lifecycle hooks

| Hook | Fires |
|---|---|
| `creating` | `OrderIsCreating($attributes)` |
| `created` | `OrderWasCreated($order)` + initial `OrderStatusHistory` row |
| `updating` | `OrderIsUpdating($order)`; if `order_status` is dirty, inserts `OrderStatusHistory` row with old + new |
| `updated` | `OrderWasUpdated($order)` |
| `deleting` | `OrderWasDeleted($order)` |

### Static methods (legacy `app()->database_manager` paths)

```php
Order::getById($id);                  // single order with relationships hydrated
Order::getOrderItems($orderId);       // cart rows linked to this order
Order::getOrders($params);            // filterable list
Order::deleteOrder($id);              // soft-delete
Order::getOrderStatuses();            // ['new', 'processing', ...] string array
Order::getPaymentStatuses();          // ['pending', 'completed', 'failed', 'refunded']
Order::getOrderCurrencies();          // distinct currency_code from cart_orders
```

These are kept for backward compatibility with legacy Microweber controllers. New code should prefer `app('order_manager')` facade or Eloquent directly.

### Reference id generation

`order_reference_id` follows the pattern `ORDER-<YmdHis>-<4 random digits>`. Generated by `OrderFactory::definition()` for tests; for real orders, [`CheckoutService::prepareOrderData()`](/modules/checkout/api#prepareorderdataarray-data-array) generates an `ORD-<crc32>` style reference and re-rolls on collision. Both formats are valid — the column is just an indexed string.

---

## OrderStatus enum

`Modules\Order\Enums\OrderStatus`. Seven values:

| Value | Filament colour | Filament icon |
|---|---|---|
| `New` | info | heroicon-o-sparkles |
| `Processing` | warning | heroicon-o-arrow-path |
| `Shipped` | success | heroicon-o-truck |
| `Delivered` | success | heroicon-o-check-badge |
| `Completed` | success | heroicon-o-check-circle |
| `Cancelled` | danger | heroicon-o-x-circle |
| `Refunded` | danger | heroicon-o-arrow-uturn-left |

Use as:

```php
$order->order_status === \Modules\Order\Enums\OrderStatus::Shipped->value
```

The enum methods `getColor(self $status)` and `getIcon(self $status)` are used by the Filament resource to colour the badge and pick the icon automatically.

---

## OrderItem-equivalent models

Order does NOT own a separate `order_items` table. Line items live in the [Cart module's](/modules/cart/) `cart` table; rows are linked to an order by setting `cart.order_id = <order_id>` during `place_order`. Use `$order->cart` (HasCartItems trait) to read them.

The other Order-owned auxiliary models:

| Model | Table | Purpose |
|---|---|---|
| `OrderStatusHistory` | `order_status_history` | one row per status transition |
| `OrderRefund` | `order_refunds` | refunds against payments on this order |
| `OrderCancelReason` | `order_cancel_reasons` | optional cancellation reason capture |
| `OrderAnonymousClient` | (no migration shown) | guest-buyer metadata side table |

---

## HTTP controllers

### `OrderApiController` (deprecated, admin-scoped)

`/api/order/*` — kept for legacy integrations.

| Method | Path | Action |
|---|---|---|
| GET | `/` | `index()` — list with `OrderFilter` |
| POST | `/` | `store()` — create |
| GET | `/{id}` | `show()` — single |
| PUT/PATCH | `/{id}` | `update()` — update |
| DELETE | `/{id}` | `destroy()` — soft-delete |

### `OrdersApiController` (current, headless)

`/api/module/orders/*` — public-readable, scope-protected for writes.

Same five verbs. Middleware: `throttle:public` on GET, `scope:orders:write` on POST/PUT/DELETE.

`OrderFilter` query-string keys: `status`, `is_paid`, `customer_id`, `email`, `from`, `to`, `min_amount`, `max_amount`, `currency`, `product`, `category`.

### `OrderExportController`

`/admin/order/export` — web-authenticated. Streams CSV of orders matched by query-string filter directly to the browser.

---

## Filament resources

### `OrderResource`

`Modules\Order\Filament\Admin\Resources\OrderResource`. Model: `Order`. Navigation group: `Shop`, sort 2. Navigation badge: count of orders in `New` status (info colour).

Pages:

| Page | Route | Purpose |
|---|---|---|
| `ListOrders` | `/admin/order` | filterable + searchable table + bulk actions |
| `CreateOrder` | `/admin/order/create` | admin creates an order manually |
| `EditOrder` | `/admin/order/{id}/edit` | 3-column form (2-col main + 1-col sidebar) |

### Form schema (EditOrder)

- **Main column (2-wide):** Tabs:
  - **Order Details** — customer fields + line-items repeater
  - **Shipping** — country/city/state/zip/address + tracking number + tracking URL
  - **Payment** — provider select, amount, currency, status, transaction id, raw payment data viewer
  - **Advanced** — custom fields + metadata
- **Sidebar (1-wide):**
  - Order Status select (`OrderStatus` enum)
  - Timestamps (created_at, updated_at)
  - Status Timeline (view component reading `statusHistory`)
  - Refunds (repeater of `OrderRefund` rows)

### Row actions

- **Edit** — opens EditOrder page
- **Delete** — soft-delete with confirmation
- **`generate_invoice`** — custom action; produces an invoice PDF (delegates to Invoice module)
- **`view_invoice`** — if invoice exists, links to it

### Bulk actions

- **Update Status** — modal with status select; transitions all selected orders + adds history rows for each
- **Export Bulk** — CSV of selection
- **Delete Bulk** — soft-deletes selection

### Header actions

- **Import** — Filament Importer (OrderImporter, CSV/Excel)
- **Export** — Filament Exporter with filter form (OrderExporter)

### Relation managers

- **`PaymentsRelationManager`** — table of `Payment` rows where `rel_type = Order::class` and `rel_id = this.order.id`. Read-only.

### Widgets

- **`OrderStats`** — dashboard widget cards (revenue, count, top products, time series). Backed by `OrderRepository` (cached `OrderStatsService`).

---

## Events

| Event | Constructor | Fired by |
|---|---|---|
| `OrderIsCreating` | `(array $attributes)` | `Order::creating()` boot hook |
| `OrderWasCreated` | `(Order $order)` | `Order::created()` boot hook |
| `OrderIsUpdating` | `(Order $order)` | `Order::updating()` boot hook |
| `OrderWasUpdated` | `(Order $order)` | `Order::updated()` boot hook |
| `OrderWasDeleted` | `(Order $order)` | `Order::deleting()` boot hook |
| `OrderWasPaid` | `(Order $order, $utmData = null)` | `OrderService::place_order()` when `is_paid === 1`; also from `Checkout::markOrderAsPaid()` on async gateway return |
| `OrderWasCanceled` | `(Order $order, $utmData = null)` | admin cancel action (Filament) |
| `OrderWasDestoyed` *(sic)* | `(Order $order)` | **not fired anywhere — dead code** |

---

## Listeners

| Listener | Listens to | Effect |
|---|---|---|
| `OrderCreatedListener` | `OrderWasCreated` | If `order_email_send_when === 'order_received'`, sends `NewOrderNotification` to all admins via database + mail channels. Also deletes admin notifications older than 30 days as housekeeping. |
| `OrderWasPaidListener` | `OrderWasPaid` | If `order_email_send_when === 'order_paid'`, sends `NewOrderNotification` (same notification, different trigger). |
| `PaymentListener` | Payment events from the Payment module | If `payment.rel_type === Order::class`, calls `$order->calculateNewAmounts()` (currently a stub — reserved for future settlement logic). |

The shared notification logic lives in `NewOrderNotificationTrait` so the two listeners stay DRY.

---

## Notifications

### `NewOrderNotification`

`Modules\Order\Notifications\NewOrderNotification`. Channels: `database` + `AppMailChannel` (project's custom mail channel).

Mail body: rendered via Twig from the template id stored in option `new_order_mail_template` (group `orders`). Variables: `order_id`, `order_reference_id`, `amount`, `currency`, `cart_items` (HTML table), `customer name/email/phone`, `address`, `shipping`, `taxes`, `transaction_id`, `is_paid`, `created_at`. Fallback (when no template configured): generic "Thank you" body.

Database body: the entire Order model as an array. Icon: heroicon-o-shopping-bag.

---

## Helpers

Order does NOT auto-load any global helpers. Legacy global functions like `get_orders($params)` and `update_cart()` come from older modules and are not the supported API. Use `app('order_manager')` or `Order::*` static methods instead.

---

## Tests

`Modules/Order/Tests/`:

| File | Coverage |
|---|---|
| `Unit/OrderManagerTest.php` | `place_order`, `save`, `get_by_id`, `get_items`, XSS sanitization, exception paths |
| `Unit/OrderImportExportTest.php` | CSV round-trip, dupe detection, update mode |
| `Unit/OrderApiControllerTest.php` | REST endpoint shapes |
| `Filament/OrderResourceTest.php` | resource model binding |
| `Unit/Filament/OrderResourceTest.php` | ListOrders list/search, CreateOrder form validation, EditOrder load, navigation badge |
