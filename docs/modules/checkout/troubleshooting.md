# Troubleshooting

Common Checkout module issues with diagnostic steps.

---

## `/checkout` redirects to the homepage immediately

**Symptom.** Browser navigates to `/checkout`, the URL flips to `/` (or to your shop page) without rendering the wizard.

**Cause.** The `CheckoutEmptyCart` middleware redirects when `cart_manager->get_cart()` returns an empty array.

**Diagnosis.**

```php
// In tinker or a temporary route:
dd(app('cart_manager')->get_cart());
```

If the result is `[]` or `null`, the cart truly is empty — add an item via `Cart::add(...)` or the Shop UI before retrying.

If the cart looks populated but the redirect still fires, you're probably session-mismatched: the cart was created in one session id and the browser hit `/checkout` with a different one. Confirm:

```php
dd(session()->getId(), app('cart_manager')->get_cart()->pluck('session_id')->unique());
```

The `session_id` on the cart rows must match `session()->getId()`. If it doesn't, your reverse-proxy or load-balancer is rotating sessions — check the `SESSION_DOMAIN`, `SESSION_SECURE_COOKIE`, and `SESSION_DRIVER` env values.

---

## "You must log in to checkout" redirect loop

**Symptom.** Browser bounces between `/checkout` and `/admin/login?redirect=/checkout` forever.

**Cause.** `shop_require_registration` is `1`, the user clicks Login, the login completes, but the session is not persisting across the redirect.

**Diagnosis.**

```php
// On the page after login (before the wizard):
dd(auth()->check(), session()->all());
```

If `auth()->check()` is `false` after the login form submit, the auth guard isn't seeing the session. Common causes:

- `APP_URL` doesn't match the actual host the user is browsing (cookie domain mismatch).
- `SESSION_DOMAIN` is set to a parent domain that doesn't include the login subdomain.
- The browser is rejecting third-party cookies and the login form was POSTed to a different domain than the wizard.

If `auth()->check()` is `true` but `shop_require_registration` still fires, check the website option value:

```php
echo \MicroweberPackages\Option\Models\Option::getValue('shop_require_registration', 'website');
```

If it returns `1`, switch it to `0` temporarily to confirm the rest of the flow works:

```php
\MicroweberPackages\Option\Models\Option::setValue('shop_require_registration', 0, 'website');
```

---

## Payment return URL returns "Invalid token" / order stuck unpaid

**Symptom.** User completes payment at the gateway, lands on `/checkout/failed?order=ORD-...`, the order row still has `is_paid = 0`.

**Cause.** The `payment_verify_token` on the order row does not match the decrypted `payment_verify_hash` from the return URL.

**Diagnosis.** Inspect both values:

```php
$order = \Modules\Order\Models\Order::where('order_reference_id', 'ORD-...')->first();
echo $order->payment_verify_token;
echo \Illuminate\Support\Facades\Crypt::decryptString(request('payment_verify_hash'));
```

If the decrypt throws → the gateway tampered with or truncated the hash query param, or your `APP_KEY` was rotated between `processPayment()` and `return`. **Rotating APP_KEY mid-checkout breaks in-flight payments.** Schedule key rotations during maintenance windows with the gateway in sandbox mode.

If the decrypt succeeds but the strings differ → the gateway sent the wrong token. Check the gateway's webhook log for the original `payment_verify_token` value Microweber generated; mismatched values usually mean the gateway is replaying an old session.

---

## Confirmation email never arrives

**Symptom.** Order is paid, the buyer is redirected to `/checkout/success`, but no email reaches their inbox.

**Diagnosis steps in order.**

1. **Is the email feature enabled?**
   ```php
   echo \MicroweberPackages\Option\Models\Option::getValue('order_email_enabled', 'orders'); // expect 1
   ```
   If `0`, `confirmEmailSend()` early-returns silently.

2. **Does the `new_order` template exist?**
   ```php
   $tpl = get_mail_template_by_type('new_order');
   dd($tpl?->subject, $tpl?->body, $tpl?->enabled);
   ```
   If `null` or `enabled = 0`, the email is suppressed. Create one in admin → Mail Templates, or:
   ```php
   \MicroweberPackages\Mail\Models\MailTemplate::create([
       'type' => 'new_order',
       'subject' => 'Order {order_id} confirmed',
       'body' => '<p>Thanks {first_name}, your order is {order_id}.</p>',
       'enabled' => 1,
   ]);
   ```

3. **Is Laravel mail configured?**
   ```php
   php artisan tinker --execute="Mail::raw('test', fn(\$m) => \$m->to('you@example.com')->subject('test'));"
   ```
   If this errors with "Driver not configured", your `MAIL_*` env vars are wrong. The mailer must be set before checkout will send.

4. **Run the test helper, bypassing the enabled check:**
   ```php
   checkout_confirm_email_test([
       'order_id' => 12345,
       'to'       => 'you@example.com',
   ]);
   ```
   This skips the `order_email_enabled` flag and surfaces any rendering errors directly.

---

## Wizard shows "No shipping methods available"

**Symptom.** Buyer reaches the shipping step but the radio is empty.

**Cause.** No `ShippingProvider` rows with `is_active = 1`, or every provider returned `null` for the typed address.

**Diagnosis.**

```php
$providers = \MicroweberPackages\Shipping\Models\ShippingProvider::where('is_active', 1)->get();
dd($providers->pluck('title', 'id'));
```

If empty, create at least one — see the [examples page](./examples.md#3-registering-a-custom-shipping-method).

If providers exist but the radio is still empty, the per-provider cost lookup is returning `null` for this buyer. Check the driver's logic for the typed zip/country — try with a known-good UK postcode like `W1A 1AA`. The fixed-rate driver also enforces a `min_total` setting; if the cart subtotal is below it, the provider hides itself.

---

## Wizard shows "No payment methods available"

**Symptom.** Buyer reaches the payment step but the radio is empty.

**Cause.** No `PaymentProvider` rows with `is_active = 1`.

**Diagnosis.**

```php
$providers = \MicroweberPackages\Payment\Models\PaymentProvider::where('is_active', 1)->get();
dd($providers->pluck('title', 'id'));
```

For local testing without a real gateway, create the built-in Pay-on-Delivery provider:

```php
\MicroweberPackages\Payment\Models\PaymentProvider::create([
    'title' => 'Pay on delivery',
    'driver' => 'pay_on_delivery',
    'is_active' => 1,
    'settings' => json_encode(['no_redirect' => true]),
]);
```

This driver returns success immediately without a redirect — exactly what's needed for end-to-end testing.

---

## `OrderWasPaid` listener fires twice

**Symptom.** Your custom listener (e.g. the CRM push from [example 2](./examples.md#2-custom-listener-on-orderwaspaid--push-to-external-crm)) executes twice for the same order — once when the gateway IPN arrives, once when the buyer's return-URL hits.

**Cause.** Both `CheckoutPaymentController::return` and `::notify` call `markOrderAsPaid()`. The early-return idempotency guard in `markOrderAsPaid()` (skip if already `is_paid = 1`) should prevent the second fire — but the **listener** runs at event-dispatch time, which happens *before* the idempotency check on the second call if your code path differs.

**Mitigation.** Make listeners themselves idempotent:

```php
public function handle(OrderWasPaid $event): void
{
    // Use the order's payment_status as a one-shot guard
    if ($event->order->payment_status === 'crm_pushed') {
        return;
    }

    Http::post(/* ... */);

    $event->order->payment_status = 'crm_pushed';
    $event->order->save();
}
```

Or use Laravel's `ShouldBeUnique` on a queued listener so the queue worker deduplicates.

---

## Cart shows different items than the wizard reviews

**Symptom.** The cart page (`/cart`) shows 3 items, but the wizard's step 1 / step 5 shows 2.

**Cause.** Session id mismatch between the cart page and the checkout panel — they're served from different Filament panels (`admin`/null vs `checkout`) and your session cookie isn't being shared.

**Diagnosis.**

```php
// On /cart:
echo session()->getId();

// On /checkout (Filament checkout panel):
echo session()->getId();
```

If the two ids differ, your session config is panel-scoped accidentally. Set `SESSION_PATH=/` in `.env` (default) and clear all browser cookies. Also confirm there's no Filament `->authGuard('checkout')` or session-driver override on the checkout panel — it should share the default web session.

---

## Order is paid in our records but shipping is never triggered

**Symptom.** Order has `is_paid = 1` but no `OrderWasPaid` listener side-effect (label printed, shipping API call, etc.).

**Cause.** The order was paid via a path that bypassed `markOrderAsPaid()` — e.g. someone manually updated `cart_orders.is_paid` in the database, or a custom admin action set the column directly.

**Fix.** Always go through the manager API:

```php
app('checkout_manager')->mark_order_as_paid($orderId);
```

Direct column updates are not picked up by listeners — there's no model observer wired for this. If you need to backfill orders that were patched manually, fire the event yourself:

```php
$order = \Modules\Order\Models\Order::find($orderId);
event(new \Modules\Order\Events\OrderWasPaid($order));
```
