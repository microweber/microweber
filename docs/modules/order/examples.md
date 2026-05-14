# Examples

Five end-to-end recipes for common Order module integrations.

---

## 1. Query orders with the filter chain

`Order::getOrders($params)` returns a filtered list via the `OrderFilter` model-filter:

```php
use Modules\Order\Models\Order;

// All paid completed orders in 2026
$orders = Order::getOrders([
    'status'   => 'completed',
    'is_paid'  => 1,
    'from'     => '2026-01-01',
    'to'       => '2026-12-31',
]);

// Top 10 highest-value orders this month
$highValue = Order::query()
    ->where('order_status', 'completed')
    ->where('created_at', '>=', now()->startOfMonth())
    ->orderByDesc('amount')
    ->limit(10)
    ->get();

// Orders for a specific customer
$customerOrders = Order::where('customer_id', $customerId)
    ->with(['statusHistory', 'refunds', 'payments'])
    ->latest()
    ->paginate(20);
```

For the headless API equivalent:

```bash
curl 'http://your-site/api/module/orders?status=completed&is_paid=1&from=2026-01-01' \
  | jq '.data | length'
```

Both paths run through `OrderFilter`, so the same keys work in both contexts.

---

## 2. Listen to `OrderWasPaid` — deduct stock + push to analytics

`OrderWasPaid` is the canonical "this order is real money now" extension point. Register listeners in `app/Providers/EventServiceProvider.php`:

```php
protected $listen = [
    \Modules\Order\Events\OrderWasPaid::class => [
        \App\Listeners\DeductStockOnOrderPaid::class,
        \App\Listeners\PushOrderToGA4::class,
    ],
];
```

Stock deduction listener:

```php
namespace App\Listeners;

use Modules\Order\Events\OrderWasPaid;
use MicroweberPackages\Product\Models\Product;

class DeductStockOnOrderPaid
{
    public function handle(OrderWasPaid $event): void
    {
        $order = $event->order;

        foreach ($order->cart as $line) {
            $product = Product::find($line->rel_id);
            if (! $product || ! $product->track_inventory) {
                continue;
            }
            // Idempotency guard — don't deduct twice if the listener fires
            // again (e.g. async webhook replay).
            $marker = 'mw_stock_deducted_for_order_' . $order->id;
            if ($product->getCustomField($marker)) {
                continue;
            }
            $product->qty = max(0, ($product->qty ?? 0) - $line->qty);
            $product->save();
            $product->setCustomField($marker, 1);
        }
    }
}
```

GA4 push listener:

```php
use Illuminate\Support\Facades\Http;

class PushOrderToGA4
{
    public function handle(OrderWasPaid $event): void
    {
        $order = $event->order;

        Http::post('https://www.google-analytics.com/mp/collect', [
            'client_id' => $order->session_id ?? 'server',
            'events'    => [[
                'name'   => 'purchase',
                'params' => [
                    'transaction_id' => $order->order_reference_id,
                    'value'          => (float) $order->amount,
                    'currency'       => $order->currency,
                    'items'          => $order->cart->map(fn ($r) => [
                        'item_id'   => $r->rel_id,
                        'item_name' => $r->title,
                        'quantity'  => (int) $r->qty,
                        'price'     => (float) $r->price,
                    ])->all(),
                ],
            ]],
        ]);
    }
}
```

---

## 3. Custom Filament row action — "Mark shipped + auto-set tracking"

Add an action that wraps the two-step (status + tracking) update in one click:

```php
// In a service provider or by extending OrderResource:

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;

Action::make('mark_shipped')
    ->label('Mark shipped')
    ->icon('heroicon-o-truck')
    ->color('success')
    ->form([
        TextInput::make('shipping_tracking_number')
            ->required()
            ->placeholder('e.g. RM12345AB'),
        TextInput::make('shipping_tracking_url')
            ->url()
            ->placeholder('https://track.example.com/RM12345AB'),
    ])
    ->action(function (Order $record, array $data): void {
        $record->order_status = OrderStatus::Shipped->value;
        $record->shipping_tracking_number = $data['shipping_tracking_number'];
        $record->shipping_tracking_url    = $data['shipping_tracking_url'] ?? null;
        $record->save();
        // OrderStatusHistory row auto-created by Order::updating()
    });
```

Add this to the `OrderResource` table actions list (or extend via `getTableActions()` override) — staff get a focused workflow that produces a consistent audit trail.

---

## 4. Monthly revenue report (using OrderStatsService)

```php
use Illuminate\Support\Carbon;

$months = collect();
for ($m = 0; $m < 12; $m++) {
    $start = Carbon::now()->startOfYear()->addMonths($m);
    $end   = (clone $start)->endOfMonth();

    $months->push([
        'month'        => $start->format('M Y'),
        'revenue'      => app('order_repository')->getOrdersTotalSumForPeriod([
            'from'    => $start->toDateString(),
            'to'      => $end->toDateString(),
            'is_paid' => 1,
            'status'  => 'completed',
        ]),
        'order_count'  => app('order_repository')->getOrdersCountForPeriod([
            'from'   => $start->toDateString(),
            'to'     => $end->toDateString(),
            'status' => 'completed',
        ]),
    ]);
}

return view('admin.reports.monthly-revenue', ['months' => $months]);
```

Because we call `app('order_repository')` (not `app(OrderStatsService::class)`), each stat is cached for 60 seconds. For a real-time dashboard widget, prefer the cached repository; for a one-shot CSV export, the underlying service is fine.

---

## 5. Refund flow — process the gateway, log the refund row, transition status

A real refund touches three modules. Order owns the bookkeeping; Payment owns the gateway call; Checkout owns the customer notification (optional).

```php
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderRefund;
use Modules\Order\Enums\OrderStatus;

DB::transaction(function () use ($orderId, $refundAmount, $reason) {
    $order = Order::findOrFail($orderId);
    $latestPayment = $order->payments()->latest()->firstOrFail();

    // 1. Ask the Payment module to call the gateway. If this throws, the
    //    DB transaction rolls back and we never log the refund row.
    $gatewayResponse = app('payment_method_manager')->refundPayment(
        $latestPayment->id,
        $refundAmount,
        $reason,
    );

    // 2. Log the OrderRefund row.
    OrderRefund::create([
        'order_id'    => $order->id,
        'payment_id'  => $latestPayment->id,
        'amount'      => $refundAmount,
        'type'        => $refundAmount >= $order->amount ? 'full' : 'partial',
        'reason'      => $reason,
        'note'        => $gatewayResponse['provider_message'] ?? null,
        'status'      => 'completed',
        'refunded_by' => auth()->id(),
    ]);

    // 3. If it's a full refund, transition the order. Status history is
    //    captured automatically by Order::updating().
    if ($refundAmount >= $order->amount) {
        $order->order_status = OrderStatus::Refunded->value;
        $order->save();
    }

    // 4. Optional — notify the customer via the MailTemplate module.
    //    Skipped here for brevity; see Checkout's confirmEmailSend() for
    //    the pattern (look up template by type, render with variables,
    //    Mail::to()->send()).
});
```

Common mistake: creating the `OrderRefund` row first and THEN trying the gateway call. If the gateway fails the row leaks and the order shows a refund that never happened. Always do the irreversible side-effect first, log the audit second — inside one transaction so the row write rolls back if the gateway fails.
