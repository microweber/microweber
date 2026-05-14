# Troubleshooting

Common Order module issues with diagnostic steps.

---

## `place_order()` throws "Cart is empty"

**Symptom.** `OrderException: Cart is empty` raised mid-checkout even though the user has items in their cart.

**Cause.** `place_order` resolves cart rows by `session_id`. The session id in the payload doesn't match the session id the cart rows were saved under.

**Diagnosis.**

```php
// What does the caller pass in?
dd($place_order['session_id']);

// What's actually in the cart for that id?
echo \Modules\Cart\Models\Cart::where('session_id', $place_order['session_id'])
    ->whereNull('order_id')
    ->count();

// What session id is Laravel currently using?
echo session()->getId();
```

If the cart query returns 0 but a different session id has rows, the request that's calling `place_order` is on a different session than the one that built the cart. Likely culprits:

- Cookie-domain mismatch (browser is sending the cookie for `example.com` but Laravel is binding the session to `www.example.com`).
- Reverse-proxy / CDN rotating session cookies.
- API call made without forwarding the buyer's session cookie.

Fix the upstream session continuity rather than guessing the right id — `place_order` is doing exactly what it should.

---

## Status history is missing rows

**Symptom.** Admin changed an order's status three times but `order_status_history` shows only one row.

**Cause.** Status was updated via raw SQL (`DB::table('cart_orders')->update(...)`) instead of via the Eloquent model. The `Order::updating()` boot hook only fires for `Eloquent` mutations.

**Fix.** Always go through the model:

```php
$order = \Modules\Order\Models\Order::find($id);
$order->order_status = 'shipped';
$order->save();
```

If you must use raw SQL (bulk migration, performance-critical job), backfill the history row yourself:

```php
\Modules\Order\Models\OrderStatusHistory::create([
    'order_id'   => $orderId,
    'old_status' => $oldStatus,
    'new_status' => $newStatus,
    'user_id'    => auth()->id(),
    'note'       => 'backfilled from SQL update',
]);
```

The same caveat applies to `Order::where(...)->update(...)` — Eloquent's mass-update bypasses model events. Use `each()` + per-row `save()` if you need history.

---

## `payment_data` returns a string, not an array

**Symptom.** `$order->payment_data` returns `'{"transaction_id":"..."}'` instead of `['transaction_id' => '...']`.

**Cause.** Order's `$casts` declaration includes `payment_data => array`, so reads should auto-decode. If it's returning a string, the column was set with a pre-encoded JSON string AND a manual call to `setRawAttributes()` or similar bypassed the cast.

**Diagnosis.**

```php
echo $order->getCasts()['payment_data'] ?? 'no cast';
// Expected: 'array'

dd($order->getRawOriginal('payment_data'));
// Expected: an array (since the cast applies on read)
```

**Fix.** Always assign as an array; let the cast handle JSON encoding:

```php
$order->payment_data = ['transaction_id' => 'tx_abc', 'gateway' => 'stripe'];
$order->save();
```

If you absolutely must store a pre-encoded string, decode it on read manually:

```php
$decoded = is_string($order->payment_data)
    ? json_decode($order->payment_data, true)
    : $order->payment_data;
```

---

## `OrderWasPaid` listener fires twice for the same order

**Symptom.** Custom listener (e.g. stock deduction from [example 2](./examples.md#2-listen-to-orderwaspaid--deduct-stock--push-to-analytics)) runs twice for one paid order.

**Cause.** Both `OrderService::place_order()` (synchronous pay-on-delivery path) and `Checkout::markOrderAsPaid()` (asynchronous gateway-return path) can fire `OrderWasPaid` for the same order if the order is patched between the two — e.g. an admin manually flipped `is_paid` to 1 during a gateway-pending window.

**Mitigation.** Make listeners idempotent. The example shown uses a custom-field marker (`mw_stock_deducted_for_order_{id}`) to guard the deduction. For listeners with heavier side-effects (CRM push, accounting entries) consider queued + `ShouldBeUnique`:

```php
class PushOrderToCRM implements ShouldQueue, ShouldBeUnique
{
    public function uniqueId(): string
    {
        return 'crm-push-' . $this->event->order->id;
    }

    public function handle(OrderWasPaid $event): void { /* ... */ }
}
```

The queue worker dedupes by `uniqueId()` so a second fire while the first is still queued is dropped.

---

## Admin can't see new orders in the Filament list

**Symptom.** Order was just placed (Order row exists in `cart_orders`) but it doesn't appear in `/admin/order`.

**Diagnosis.**

```php
\Modules\Order\Models\Order::orderByDesc('id')->limit(5)->get(['id', 'order_status', 'deleted_at']);
```

Check:

1. Is `deleted_at` set? → the row is soft-deleted. The Filament list filters them out by default.
2. Is `order_status` set to an unexpected value? → check the active filter in the Filament list (a status filter may be active).
3. Is `customer_id` set to a customer that the current admin can't see? → no — Filament's OrderResource doesn't scope by customer ownership by default. Skip this if you haven't added a custom scope.

If the row exists with `deleted_at = null` and matching filters, hard-refresh the Filament page; the Livewire component may be holding a cached list. Filament 5 invalidates on `OrderWasCreated` only if a Filament refresh listener is wired — for headless `place_order` calls outside the admin context, the admin browser won't update until reload.

---

## Refund amount doesn't reduce the order total

**Symptom.** Created an `OrderRefund` row for $20 but `order->amount` still shows the original $99.

**Cause.** By design — `order->amount` is the **original transaction total**, not a running balance. Refunds are tracked in the `order_refunds` table; the running balance is computed at read time.

**Fix.** Compute net amount on demand:

```php
$grossAmount  = (float) $order->amount;
$totalRefunds = $order->refunds()->where('status', 'completed')->sum('amount');
$netAmount    = $grossAmount - $totalRefunds;
```

If you want a `net_amount` accessor on the Order model:

```php
// in Order.php
public function getNetAmountAttribute(): float
{
    return (float) $this->amount - (float) $this->refunds()
        ->where('status', 'completed')
        ->sum('amount');
}
```

Then `$order->net_amount` works as expected. The audit trail in `order_refunds` is more useful long-term than rewriting `cart_orders.amount` — auditors can see exactly when each refund was logged and by whom.

---

## "Status Timeline" tab is empty on the Filament order detail

**Symptom.** Sidebar's Status Timeline view component on `EditOrder` shows no entries.

**Cause.** Either no status transitions have occurred yet (only `created` happened — that's logged but may not render), OR `$order->statusHistory` is being eager-loaded after the view is rendered.

**Diagnosis.**

```php
$order = \Modules\Order\Models\Order::with('statusHistory')->find($id);
dd($order->statusHistory->count());
```

If count > 0 but the view shows empty:

- Check the Blade view path (likely `modules.order::filament.partials.status-timeline`) — Filament's view component may be loading the wrong template.
- Confirm the Filament page's `getRecord()` returns the order with `statusHistory` eager-loaded — otherwise lazy-loading inside the view runs once but the view-component caches.

If count is 0 but the order has clearly had its status changed via the Filament form:

- The `Order::updating()` boot hook didn't fire — confirm the model class being used is `Modules\Order\Models\Order` (not a copy or a project-local override).
- The `order_status` column wasn't actually dirty — Filament's optimistic update may have set the same status the order already had.

---

## `getOrdersTotalSumForPeriod()` returns stale data

**Symptom.** Just placed a new $100 order but the dashboard widget still shows the pre-order revenue total.

**Cause.** `OrderRepository` caches stats calls for 60s. The widget pulls through the cache.

**Diagnosis.**

```php
\Cache::forget('order_stats_total_sum_*'); // or whatever cache key pattern
echo app('order_repository')->getOrdersTotalSumForPeriod($params);
```

**Fix.** For real-time admin widgets, use `OrderStatsService` directly (uncached):

```php
echo app(\Modules\Order\Services\OrderStatsService::class)
    ->getOrdersTotalSumForPeriod($params);
```

For dashboard cards on a busy admin where staleness up to 60s is acceptable, the cached repository is the right choice — saves the DB hit on every reload.

Cache invalidation is wired to `OrderService::save()`. If you're updating orders through raw SQL or direct `Order::query()->update()`, manually clear:

```php
\Cache::tags(['order_stats'])->flush();
```

---

## Pre-existing `Modules/Order/docs/README.md` conflicts with the VitePress docs

**Symptom.** A reader following module documentation finds two answers in two places.

**Resolution.** `Modules/Order/docs/README.md` is the **auto-regenerated** module-level snapshot (last refreshed 2026-04-25). The VitePress docs at `docs/modules/order/` are the **canonical narrative**. When the two disagree:

- For **what classes / events / columns exist** → trust `Modules/Order/docs/README.md` (it's regenerable from the filesystem).
- For **how/why to use them + cross-module relationships** → trust the VitePress docs.

If you find drift, regenerate the module-level snapshot (the project has a regeneration script — see `docs/modules/MODULE_DOCS_TEMPLATE.md`), and update this VitePress page if any architectural fact changed.
