# Installation

The Checkout module ships as part of the Microweber core. There's nothing to `composer require` — it's already registered in your install. This page documents what the module pulls in, how it's wired, and the configuration knobs that affect its behaviour.

---

## Service provider

`Modules\Checkout\Providers\CheckoutServiceProvider` is registered automatically via `module.json`:

```json
{
  "providers": [
    "Modules\\Checkout\\Providers\\CheckoutServiceProvider"
  ],
  "files": [
    "Support/helpers.php"
  ]
}
```

The provider:

- Registers the `checkout_manager` singleton in the container (resolves to `Modules\Checkout\Repositories\CheckoutManager`).
- Boots routes from `routes/web.php` and `routes/api.php`.
- Loads Blade views under the `modules.checkout::` namespace.
- Registers the Livewire components (`CheckoutWizard`, `CartItems`, `ReviewOrder`).
- Auto-loads `Support/helpers.php` so `checkout()`, `checkout_get_user_info()`, `checkout_set_user_info()`, and `checkout_confirm_email_test()` are available as global functions.

`Modules\Checkout\Providers\FilamentCheckoutPanelProvider` registers a second Filament panel keyed `checkout` so the wizard pages render with the customer-facing theme. The admin panel and checkout panel share the same `MwColors::Blue` brand token (see [AI-209 / AI-211](/modules/cart/usage.md#admin-checkout-public-color-unification) for the unification rationale).

---

## Route loading

| File | Mount point | Loaded by |
|---|---|---|
| `routes/api.php` | `/api/module/checkout/*` | `CheckoutServiceProvider::boot()` (api middleware group) |
| `routes/web.php` | `/api/checkout/payment/{return,cancel,notify}` | `CheckoutServiceProvider::boot()` (web middleware group) |

All `/api/module/checkout/*` endpoints carry the `throttle:public` middleware alongside `api`, so anonymous traffic is rate-limited. The payment return / cancel / notify endpoints are *not* rate-limited because gateways may retry IPNs.

---

## Filament panel registration

The customer-facing Checkout panel is registered through `FilamentCheckoutPanelProvider`. It declares:

- panel id: `checkout`
- path: `/checkout`
- pages: `CheckoutPage`, `CheckoutSuccessPage`, `CheckoutFailedPage`, `CheckoutCancelledPage`
- theme: `MwColors::Blue` (Bootstrap primary)
- form border-radius: 4px (AI-211 unification override on `body.fi-panel-checkout`)

If you create additional checkout-flow pages, register them as `Pages` on this panel — do **not** add them to the admin panel.

---

## Required configuration

Checkout reads website options (admin → settings) via `get_option(...)`. The relevant keys:

| Option key | Group | Default | Effect |
|---|---|---|---|
| `shop_require_email` | website | `1` | If `1`, email is mandatory at the contact step. |
| `shop_require_first_name` | website | `1` | If `1`, first_name is mandatory at the contact step. |
| `shop_require_terms` | website | `0` | If `1`, the user must accept terms before submitting the wizard. |
| `shop_require_registration` | website | `0` | If `1`, guests are redirected to login before the wizard renders. `CheckoutEmptyCart` middleware enforces this. |
| `order_email_enabled` | orders | `1` | If `0`, `confirmEmailSend()` returns immediately without sending. |
| `enable_taxes` | shop | `1` | If `1`, `cart_get_tax()` is added to the order's `taxes_amount`. |
| `currency` | payments | `USD` | Default currency for the order row and the payment driver. |

You can change these from the admin **Settings → Shop** panel or programmatically:

```php
\MicroweberPackages\Option\Models\Option::setValue('shop_require_terms', 1, 'website');
```

---

## Encryption requirement

The payment verify token is built with `Crypt::encryptString()` using `config('app.key')`. If you regenerate the key after taking payments, in-flight checkout sessions will fail verification on return. Plan key rotations to happen during a maintenance window with the gateway sandbox in use.

---

## Dependencies on other modules

Checkout depends on the following sibling modules being installed and enabled:

| Module | Reason |
|---|---|
| **[Cart](/modules/cart/)** | reads line items + totals via `CartService::getCart()` and `cart_manager->get_cart()` |
| **Order** | creates `cart_orders` rows via `order_manager->place_order()` |
| **Payment** | fetches `PaymentProvider` records and drives gateway calls via `payment_method_manager` |
| **Shipping** | fetches `ShippingProvider` records and gets quotes via `shipping_method_manager` |
| **MailTemplate** | renders the order confirmation email via `MailTemplateService::createMailable()` |
| **User** | session id, login state, shipping address via `user_manager` |
| **Content** | first-shop-page lookup for redirect when the cart is empty |
| **Country** | country list + name lookup for the contact step |

If any of these are disabled, Checkout will surface a clear error at boot — it does not silently degrade.

---

## Database

Checkout owns **no migrations**. Every persisted record lives in another module's table:

| Table | Owner | What Checkout does |
|---|---|---|
| `cart_orders` | Order module | populates on `place_order()`; updates `is_paid`, `payment_status`, `transaction_id`, `payment_data` on gateway return |
| `cart` | Cart module | reads line items to render the cart step + email body |
| `payments` | Payment module | creates one row per successful charge, `rel_type='orders'`, `rel_id=$order->id` |
| `shipping_providers` | Shipping module | read-only lookup by id |
| `payment_providers` | Payment module | read-only lookup by id |

Run the Order, Cart, Payment, and Shipping migrations as part of the normal Microweber install — there's no Checkout-specific migration step.

---

## Sanity check

After deploy, hit these URLs to confirm the module is wired:

```bash
# REST shipping/payment discovery (returns JSON arrays)
curl http://your-site/api/module/checkout/shipping-methods
curl http://your-site/api/module/checkout/payment-methods

# Filament page (returns the wizard HTML)
curl -I http://your-site/checkout

# Helper available in tinker
php artisan tinker --execute='echo checkout_url();'
```

If `/checkout` returns 404, double-check that `FilamentCheckoutPanelProvider` is in your `config/app.php` providers list (Modules normally register themselves automatically, but a custom app config may bypass that).
