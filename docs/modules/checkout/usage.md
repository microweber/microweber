# Usage

This page walks through the Checkout module's runtime surfaces — the wizard, the REST API, the payment-callback round-trip, and the hooks downstream modules use to react.

---

## Starting the wizard

The customer-facing wizard is a Filament page mounted at `/checkout` (panel id `checkout`):

```
GET /checkout
```

Rendered by `Modules\Checkout\Filament\Resources\CheckoutResource\Pages\CheckoutPage`, which loads the `modules.checkout::filament.pages.checkout-wizard-page` view containing the `<livewire:checkout-wizard />` component.

### Step navigation

The wizard's current step is mirrored in the URL query string so back/forward and refresh preserve position:

```
/checkout?step=cart
/checkout?step=contact
/checkout?step=shipping
/checkout?step=payment
/checkout?step=review
```

If the user lands on `/checkout` with an empty cart, the `CheckoutEmptyCart` middleware redirects them to the first content-type-`shop` page (resolved via `content_repository->getFirstShopPage()`). If no shop page exists, the redirect falls back to the site root.

If `shop_require_registration` is `1` and the user is not logged in, the same middleware redirects to the login page with `?redirect=/checkout`.

---

## Pre-filling the contact step

`CheckoutWizard::mount()` pre-fills the contact form from three sources in priority order:

1. The user's profile (if logged in) — `user_manager->id()` → User model → `first_name`, `last_name`, `email`, `phone`.
2. The user's saved shipping address (if any) — `user_manager->get_shipping_address()`.
3. The session checkout data — `CheckoutService::getUserInfo()` (anything previously typed into the wizard and not yet submitted).

Custom pre-fills (e.g. from a query string) can be done in a custom page via:

```php
checkout_set_user_info('email', $request->query('email'));
checkout_set_user_info('first_name', $request->query('first_name'));
```

These persist in the session keyed under `checkout`.

---

## Programmatic checkout

To run a checkout server-side (e.g. for an admin "create order on behalf of" flow), call the helper:

```php
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
    'shipping_provider_id' => 4,
    'payment_provider_id'  => 2,
    'terms'      => 1,
]);

if ($result['success']) {
    // $result['order_id'], $result['order_completed'], $result['is_paid'],
    // $result['redirectUrl'] (if the gateway redirects), $result['transaction_id']
} else {
    // $result['error'] string with details
}
```

The helper is a thin wrapper around `app('checkout_manager')->checkout($data)` which delegates to `CheckoutService::checkout()`.

---

## Guest vs registered checkout

The `create_account` flag on the contact step decides the path:

| `create_account` | User logged in? | Outcome |
|---|---|---|
| omitted / 0 | no | guest order — order row created, no user provisioning, confirmation email sent to the typed address |
| omitted / 0 | yes | order linked to the logged-in user automatically |
| 1 | no | wizard validates `password` + `password_confirmation`; creates a User row; links the order; emails both order confirmation + welcome |
| 1 | yes | flag is ignored (user already exists) |

Forcing all checkouts to require login: set the `shop_require_registration` option to `1`.

---

## Shipping methods

The shipping step loads available providers at mount via:

```php
GET /api/module/checkout/shipping-methods
→ ['id' => 4, 'title' => 'Royal Mail Tracked 24', 'is_active' => 1, ...]
```

The user picks one (radio). The wizard then queries the cost for the chosen provider against the typed shipping address:

```php
POST /api/module/checkout/calculate-shipping
{
  "shipping_provider_id": 4,
  "country": "GB", "city": "London", "zip": "W1A 1AA"
}
→ { "cost": 6.50, "currency": "GBP" }
```

Cost is calculated by the Shipping module (`shipping_method_manager->getShippingCost(...)`). Checkout just relays.

If the provider returns an error (out-of-area, weight exceeded, no postal code coverage), the wizard surfaces it under the radio option and the user can pick another.

---

## Payment methods

The payment step works the same way:

```php
GET /api/module/checkout/payment-methods
→ ['id' => 2, 'title' => 'Stripe', 'is_active' => 1, 'form_view' => 'modules.payment::stripe.form', ...]
```

The selected provider's `form_view` is rendered inline; gateway-specific fields (Stripe Elements, PayPal smart button) render their own widgets.

On final submit, `CheckoutService::processPayment($providerId, &$orderData)`:

1. Generates a random `payment_verify_token`.
2. Encrypts it with `config('app.key')` to produce a `payment_verify_hash`.
3. Builds the three return URLs with `order_reference_id`, `payment_verify_token`, and `payment_verify_hash` embedded.
4. Calls `payment_method_manager->process($providerId, $orderData)`.

The driver returns a response with:

```php
[
  'success'       => true,
  'redirectUrl'   => 'https://gateway.example.com/pay/...',  // if the gateway redirects
  'transactionId' => 'pi_3OxKbW2eZvKYlo2C0V8m9wY1',
  'error'         => null,
]
```

The wizard surfaces `redirectUrl` to the browser as a JavaScript redirect; the driver may also push the user into an iframe or inline card form depending on its `form_view`.

---

## Payment callback round-trip

After the user pays at the gateway, the gateway hits one of three URLs:

| URL | Triggered by | Verifies |
|---|---|---|
| `GET /api/checkout/payment/return` | gateway redirect on success | token + hash matches encrypted store; driver `verifyPayment()` returns success |
| `GET /api/checkout/payment/cancel` | user cancelled at gateway | token matches; order marked cancelled; redirect to checkout-cancelled page |
| `POST /api/checkout/payment/notify` | gateway IPN webhook | gateway signature + token match; updates order asynchronously |

All three funnel through `CheckoutPaymentController::handlePaymentResponse()`. On success:

1. `Order::find($orderReferenceId)` resolves the in-flight order.
2. `payment_method_manager->verifyPayment($providerId, $request)` re-verifies with the gateway.
3. If verified → `markOrderAsPaid($orderId)` fires `OrderWasPaid` + legacy `mw.cart.checkout.order_paid` + creates the `payments` row.
4. The buyer is redirected to `/checkout/success?order=<reference_id>`.

If verification fails, the buyer goes to `/checkout/failed?order=<reference_id>` and the order keeps `is_paid = 0`.

---

## Listening to `OrderWasPaid`

When you want a custom side-effect at the moment an order flips to paid (e.g. push to an external CRM, fire an analytics event, trigger inventory deduction), listen for `OrderWasPaid`:

```php
namespace App\Listeners;

use Modules\Order\Events\OrderWasPaid;

class PushOrderToCRM
{
    public function handle(OrderWasPaid $event): void
    {
        $order = $event->order;
        // $order is the Order model — id, order_reference_id, amount,
        // currency, items_count, payment_provider_id, etc.
    }
}
```

Register the listener in `app/Providers/EventServiceProvider.php`:

```php
protected $listen = [
    \Modules\Order\Events\OrderWasPaid::class => [
        \App\Listeners\PushOrderToCRM::class,
    ],
];
```

The legacy `mw.cart.checkout.order_paid` global event is still fired alongside `OrderWasPaid` for older listeners; new code should prefer the typed event.

---

## Order confirmation email

`CheckoutService::confirmEmailSend($orderId)` is called automatically at the end of the pipeline. It:

1. Fetches the `new_order` mail template via `get_mail_template_by_type('new_order')`.
2. Loads the order + its cart items (`get_cart('order_id=' . $order->id)`).
3. Builds variables: `order_id`, `transaction_id`, `amount`, `date`, customer name + email + phone, shipping + billing address, items table HTML.
4. Creates a Mailable via `MailTemplateService::createMailable($template, $variables)`.
5. Sends with `Mail::to($order->email)->send($mailable)`.

To preview a template without actually completing a checkout:

```php
checkout_confirm_email_test([
    'order_id' => 12345,
    'to' => 'qa@example.com',
]);
```

To disable the email entirely (e.g. while testing in production), set the website option:

```php
\MicroweberPackages\Option\Models\Option::setValue('order_email_enabled', 0, 'orders');
```

---

## REST API summary

| Endpoint | Method | Purpose |
|---|---|---|
| `/api/module/checkout` | GET | Returns current session checkout state (buyer info + step + cart summary). |
| `/api/module/checkout` | POST | Submits the full checkout payload — same as the wizard's final step. |
| `/api/module/checkout` | PUT | Updates a single field (e.g. `email`) without progressing the wizard. |
| `/api/module/checkout/validate` | POST | Validates a partial payload against the current step's rules. Used by the wizard for inline validation. |
| `/api/module/checkout/shipping-methods` | GET | Lists active shipping providers. |
| `/api/module/checkout/payment-methods` | GET | Lists active payment providers + their form views. |
| `/api/module/checkout/calculate-shipping` | POST | Returns the shipping cost for a provider + address combo. |
| `/api/module/checkout/order/{orderReferenceId}` | GET | Returns the current order status (paid / pending / cancelled). |

All endpoints are rate-limited via the `throttle:public` middleware. The full request/response shapes live in [API reference](./api.md#http-controllers).
