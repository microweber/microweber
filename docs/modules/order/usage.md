# Usage

How the Order module is consumed: placing orders from Checkout, listening to lifecycle events, transitioning status, refunding, exporting, and the headless API.

---

## Placing an order

`OrderService::place_order()` (called via the `order_manager` facade) is the only sanctioned entry point. It runs inside a DB transaction:

```php
$orderId = app('order_manager')->place_order([
    'first_name' => 'Ada',
    'last_name'  => 'Lovelace',
    'email'      => 'ada@example.com',
    'phone'      => '+44 7700 900123',
    'country'    => 'GB',
    'city'       => 'London',
    'state'      => 'England',
    'zip'        => 'W1A 1AA',
    'address'    => '221B Baker Street',
    'amount'     => 99.99,
    'currency'   => 'GBP',
    'shipping_provider_id' => 4,
    'shipping_provider'    => 'Royal Mail Tracked 24',
    'shipping_amount'      => 6.50,
    'payment_provider_id'  => 7,
    'payment_provider'     => 'Pay on Delivery',
    'is_paid'              => 0,
    'order_status'         => 'new',
    'session_id'           => session()->getId(),  // required to link cart rows
]);
```

What happens internally:

1. `OrderIsCreating` fires (validators can throw to reject).
2. Cart rows for the given `session_id` are linked to the new `order_id` (their `order_id` column is updated).
3. `cart_orders` row is inserted; `Order::created()` boot hook fires `OrderWasCreated`.
4. `order_status_history` gets its first row: `old_status = null, new_status = 'new'`.
5. If `is_paid = 1` in the payload → `OrderWasPaid` fires + `checkout_manager->mark_order_as_paid()` is called.
6. `checkout_manager->after_checkout()` runs (inventory deduction listeners, etc.).
7. Returns the inserted `order_id`. Throws `OrderException` on validation failure (empty cart, missing required field).

In practice, `place_order` is called from [Checkout's pipeline](/modules/checkout/usage#the-checkout-pipeline-checkoutservicecheckout), not from app code. If you need to seed an order programmatically (admin "create on behalf of" flow, fixture loader), call it directly — but ensure the cart is populated first under the same `session_id`.

---

## Listening to lifecycle events

The most common extension point is `OrderWasPaid`. Register a listener in `app/Providers/EventServiceProvider.php`:

```php
protected $listen = [
    \Modules\Order\Events\OrderWasPaid::class => [
        \App\Listeners\DeductStockOnOrderPaid::class,
        \App\Listeners\PushPaidOrderToAnalytics::class,
    ],
];
```

The listener gets the Order model directly:

```php
public function handle(\Modules\Order\Events\OrderWasPaid $event): void
{
    $order = $event->order;
    // $order->id, $order->order_reference_id, $order->amount, $order->currency,
    // $order->cart (HasCartItems trait), $order->payments (MorphMany)
}
```

`OrderWasPaid` is the contract. The other events (`OrderWasCreated`, `OrderWasUpdated`, etc.) are also dispatchable but think twice before using them:

- `OrderWasCreated` fires *before* payment is verified — using it for accounting will double-count when the payment later succeeds.
- `OrderWasUpdated` fires on every `save()` (status change, tracking number update, etc.) — listeners need to check what actually changed.

The seven-event catalogue lives in `Modules\Order\Events\*`. The `OrderWasDestoyed` event (sic — typo in the class name) exists but is not fired anywhere; treat it as dead code.

---

## Status transitions

Statuses are admin-driven via the Filament order detail page. The `order_status` dropdown writes back through `Order::updated()` and `Order::updating()`, which:

1. Fires `OrderIsUpdating` (pre-save) and `OrderWasUpdated` (post-save).
2. If `order_status` is dirty, inserts a row into `order_status_history` with `old_status`, `new_status`, the acting admin's `user_id`, and an optional `note`.

For programmatic transitions:

```php
$order = \Modules\Order\Models\Order::findOrFail($id);
$order->order_status = \Modules\Order\Enums\OrderStatus::Shipped->value;
$order->shipping_tracking_number = 'RM12345AB';
$order->shipping_tracking_url = 'https://track.royalmail.com/RM12345AB';
$order->save();   // OrderStatusHistory row auto-created
```

There is **no state-machine enforcement** — you can go `New → Refunded` directly if the situation demands it (duplicate order, fraud reversal). The audit trail in `order_status_history` captures the move regardless.

---

## Refunds

Create an `OrderRefund` row to log a refund:

```php
use Modules\Order\Models\OrderRefund;

$refund = OrderRefund::create([
    'order_id'    => $order->id,
    'payment_id'  => $order->payments()->latest()->first()?->id,
    'amount'      => 24.99,
    'type'        => 'partial',        // 'full' or 'partial'
    'reason'      => 'wrong size',
    'note'        => 'Customer kept the other item; refunding the second.',
    'status'      => 'completed',      // 'completed' / 'pending' / 'failed'
    'refunded_by' => auth()->id(),
]);
```

The Filament order detail page has a "Refunds" repeater on the right sidebar — the same pattern via the admin UI.

If the refund covers the full amount, also transition the order status:

```php
$order->order_status = \Modules\Order\Enums\OrderStatus::Refunded->value;
$order->save();
```

The actual money-back gateway call is *not* triggered by `OrderRefund` creation. That's the Payment module's responsibility — call `payment_method_manager->refundPayment($paymentId, $amount)` first, then log the refund row. The Order module records the bookkeeping; the Payment module does the work.

---

## Exporting orders

### Via the Filament admin

Header "Export" action on the orders list opens a modal with filters (status, date range, customer). Filament's exporter pipes the matched orders into a CSV/Excel job.

### Programmatically

```php
$csv = app('order_manager')->export_orders([
    'is_completed' => 1,
    'from'         => '2026-01-01',
    'to'           => '2026-12-31',
]);

\Storage::disk('local')->put('orders-2026.csv', $csv);
```

`export_orders([])` (no filter) returns ALL completed orders. Pass `['order_id' => 12345]` to export a single order.

### Via the admin route

```
GET /admin/order/export?from=2026-01-01&to=2026-12-31&status=completed
```

The web-authenticated route streams the CSV directly to the browser.

---

## Importing orders

`Filament\Imports\OrderImporter` is invoked from the "Import" header action on the orders list. CSV/Excel rows are matched to existing orders by `order_reference_id` — found rows are updated, missing rows are inserted.

The importer respects the same fillable list as `place_order`. Cart-item linkage is NOT re-built on import — use this for fixing up status/tracking/payment metadata on existing orders, not for migrating a whole order with line items from another system.

---

## REST API (headless)

`/api/module/orders/*` (`OrdersApiController`):

| Method | Path | Action | Auth |
|---|---|---|---|
| GET | `/api/module/orders` | list (filterable via `OrderFilter`) | `throttle:public` |
| GET | `/api/module/orders/{order}` | show one | `throttle:public` |
| POST | `/api/module/orders` | create | `scope:orders:write` |
| PUT/PATCH | `/api/module/orders/{order}` | update | `scope:orders:write` |
| DELETE | `/api/module/orders/{order}` | destroy (soft) | `scope:orders:write` |

`OrderFilter` (`Models/ModelFilters/OrderFilter.php`) accepts query-string keys: `status`, `is_paid`, `customer_id`, `email`, `from`, `to`, `min_amount`, `max_amount`, `currency`, plus joins to filter by `product` (via cart→content).

Example:

```bash
curl 'http://your-site/api/module/orders?status=processing&min_amount=50' | jq '.data | length'
```

Legacy `/api/order/*` (`OrderApiController`) is admin-scoped and deprecated — newer integrations should use `/api/module/orders/*`.

---

## Admin notifications

When `order_email_send_when` option is `order_received`:

- `OrderCreatedListener` triggers on `OrderWasCreated` (boot-hook level).
- Enumerates all admin users.
- Sends `NewOrderNotification` to each via the `database` channel + the `AppMailChannel` (mail).
- Deletes notifications older than 30 days as a side-effect housekeeping pass.

When `order_email_send_when` is `order_paid`:

- `OrderWasPaidListener` triggers on `OrderWasPaid` instead.
- Same notification payload.

Switching between modes is a single option change — no migration needed. Existing in-flight orders use whatever mode was set at the time of their event.

---

## Stats & analytics

`OrderStatsService` (resolved via `app('order_repository')`) exposes:

| Method | Returns |
|---|---|
| `getOrdersTotalSumForPeriod($params)` | float — `SUM(amount)` |
| `getOrdersCountForPeriod($params)` | int — count |
| `getOrderItemsCountForPeriod($params)` | int — count from cart join |
| `getOrdersCountGroupedByDate($params)` | array — `[date => count]` time series |
| `getBestSellingProductsForPeriod($params)` | array — top products by cart row count |
| `getBestSellingCategoriesForPeriod($params)` | array — top categories (cart → content → category join) |

`$params` keys: `from` (Y-m-d), `to`, `currency`, `is_paid` (bool), `status`.

The Filament `OrderStats` widget consumes these and renders the admin dashboard cards.
