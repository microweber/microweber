# Examples

Five end-to-end recipes for common Checkout integrations.

---

## 1. Programmatic end-to-end checkout (admin "create order on behalf of")

Useful when staff take phone orders, or you need to seed test data:

```php
<?php

use Modules\Cart\Facades\Cart;

// 1. Build the cart server-side
Cart::add([
    'rel_type' => 'content',
    'rel_id'   => 17,           // product id
    'qty'      => 2,
    'session_id' => session()->getId(),
]);

Cart::add([
    'rel_type' => 'content',
    'rel_id'   => 23,
    'qty'      => 1,
    'session_id' => session()->getId(),
]);

// 2. Run the wizard's submit pipeline directly
$result = checkout([
    'first_name' => 'Ada',
    'last_name'  => 'Lovelace',
    'email'      => 'ada@example.com',
    'phone'      => '+44 7700 900123',
    'country'    => 'GB',
    'city'       => 'London',
    'state'      => 'England',
    'zip'        => 'W1A 1AA',
    'address'    => '221B Baker Street',
    'shipping_provider_id' => 4,      // Royal Mail Tracked 24
    'payment_provider_id'  => 7,      // Pay on Delivery (no redirect)
    'terms'                => 1,
]);

if (! $result['success']) {
    abort(422, $result['error']);
}

// 3. Order is placed; mark it paid right away because pay-on-delivery
//    completes synchronously
if ($result['order_completed'] === 0 && empty($result['redirectUrl'])) {
    app('checkout_manager')->mark_order_as_paid($result['order_id']);
}

return response()->json([
    'order_reference_id' => $result['order_reference_id'],
    'order_id'           => $result['order_id'],
]);
```

For a gateway-based payment (Stripe, PayPal), `$result['redirectUrl']` will be non-empty — the caller must redirect the browser to that URL and let `CheckoutPaymentController::return` handle the verification on return.

---

## 2. Custom listener on `OrderWasPaid` — push to external CRM

```php
<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;
use Modules\Order\Events\OrderWasPaid;

class PushPaidOrderToCRM
{
    public function handle(OrderWasPaid $event): void
    {
        $order = $event->order;

        Http::asJson()
            ->withToken(config('services.crm.token'))
            ->post(config('services.crm.url') . '/orders', [
                'reference' => $order->order_reference_id,
                'amount'    => (float) $order->amount,
                'currency'  => $order->currency,
                'customer'  => [
                    'email'      => $order->email,
                    'first_name' => $order->first_name,
                    'last_name'  => $order->last_name,
                ],
                'placed_at' => $order->created_at?->toIso8601String(),
            ])
            ->throw();
    }
}
```

Register in `app/Providers/EventServiceProvider.php`:

```php
protected $listen = [
    \Modules\Order\Events\OrderWasPaid::class => [
        \App\Listeners\PushPaidOrderToCRM::class,
    ],
];
```

`OrderWasPaid` fires both in the synchronous (pay-on-delivery) and asynchronous (gateway IPN) paths, so the CRM stays consistent regardless of how the order was paid.

---

## 3. Registering a custom shipping method

The Shipping module is the registry — Checkout just queries it. To add a "Same-day courier" method:

```php
<?php

use MicroweberPackages\Shipping\Models\ShippingProvider;

ShippingProvider::create([
    'title'       => 'Same-day courier',
    'description' => 'Hand-delivered within 4 hours, ≤10 mi from W1A',
    'driver'      => 'fixed_rate',   // built-in flat-rate driver
    'is_active'   => 1,
    'settings'    => json_encode([
        'rate'      => 14.99,
        'currency'  => 'GBP',
        'min_total' => 25.00,         // only available above this cart subtotal
        'zip_prefixes' => ['W1', 'W2', 'NW1'],
    ]),
]);
```

After this row exists, the wizard's shipping step automatically discovers it via `GET /api/module/checkout/shipping-methods`. The `fixed_rate` driver evaluates `zip_prefixes` against the typed delivery zip; if no match it returns `null` for the cost and the wizard hides the option for that buyer.

For a fully bespoke driver, implement `MicroweberPackages\Shipping\Contracts\ShippingDriver` and register it via the Shipping module's driver registry — `driver` on the provider row points to your driver key.

---

## 4. Custom return-URL handling (Stripe Checkout Session)

When using Stripe Checkout Sessions, the gateway redirects back with `?session_id=cs_test_...` rather than the standard Microweber `order_reference_id` + `payment_verify_token` pair. Bridge it with a custom route that wraps `CheckoutPaymentController::handlePaymentResponse`:

```php
// routes/web.php (app-level, not module)

Route::get('/stripe/checkout-return', function (\Illuminate\Http\Request $request) {
    $sessionId = $request->query('session_id');
    abort_unless($sessionId, 400, 'Missing session_id');

    // Look up the order we associated with this session at process time
    $order = \Modules\Order\Models\Order::query()
        ->where('payment_data->stripe_session_id', $sessionId)
        ->firstOrFail();

    // Forward to the standard return handler with the canonical params
    return redirect()->action(
        [\Modules\Checkout\Http\Controllers\CheckoutPaymentController::class, 'return'],
        [
            'order_reference_id'   => $order->order_reference_id,
            'payment_verify_token' => $order->payment_verify_token,
            'payment_verify_hash'  => encrypt($order->payment_verify_token),
        ]
    );
});
```

The session_id → order_reference_id mapping is stored at `processPayment()` time inside the order's `payment_data` JSON column by your Stripe driver.

---

## 5. Sending a custom order-confirmation email body

If you want a richer email than the default `new_order` template renders, override the template body from your admin:

```php
<?php

use MicroweberPackages\Mail\Models\MailTemplate;

MailTemplate::updateOrCreate(
    ['type' => 'new_order'],
    [
        'subject'  => 'Your order {order_id} — confirmed',
        'body'     => view('emails.order-confirmation', [])->render(),
        'enabled'  => 1,
    ]
);
```

Available variables (auto-replaced by `MailTemplateService::createMailable()`):

| Variable | Value |
|---|---|
| `{order_id}` | order_reference_id |
| `{transaction_id}` | gateway transaction id |
| `{amount}` | formatted total |
| `{currency}` | currency code |
| `{date}` | order placed-at, formatted per locale |
| `{first_name}`, `{last_name}`, `{email}`, `{phone}` | buyer contact |
| `{address}`, `{city}`, `{state}`, `{zip}`, `{country}` | shipping address |
| `{items_table}` | rendered HTML table of cart items |
| `{site_name}` | website title from options |
| `{site_url}` | canonical site URL |

To preview without taking a real order:

```php
checkout_confirm_email_test([
    'order_id' => 12345,
    'to'       => 'qa@example.com',
]);
```

The `skipEnabledCheck` flag inside that helper bypasses the `order_email_enabled` option, so QA can preview even with the production-side email disabled.
