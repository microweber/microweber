# API Reference

Class, method, route, and event signatures for the Checkout module.

---

## CheckoutService

`Modules\Checkout\Services\CheckoutService` — the orchestrator (537 lines). Singleton; resolve via `app()->make(CheckoutService::class)` or use the `checkout_manager` facade.

### `checkout(array $data): array`

Runs the full pipeline. Returns an associative array:

| Key | Type | Meaning |
|---|---|---|
| `success` | bool | overall pipeline success |
| `order_id` | int\|null | id of the `cart_orders` row created (null on early failure) |
| `order_reference_id` | string | `ORD-<crc32>` |
| `order_completed` | int | `1` if no payment is needed (pay-on-delivery), `0` until verified |
| `is_paid` | int | `1` if the gateway returned immediately; `0` if redirect-based |
| `transaction_id` | string\|null | gateway-issued transaction id |
| `redirectUrl` | string\|null | gateway URL the browser must navigate to next |
| `error` | string\|null | error message on failure |

Validates the cart, builds the order row, dispatches to the payment gateway, places the order, deducts stock if appropriate, and sends the confirmation email.

### `validateCart(): bool`

Returns `true` if `cart_manager->get_cart()` returns a non-empty array. Used by the wizard's first step and by `checkout()`'s pre-flight.

### `validateCheckoutData(array $data): array`

Returns a validation error array (empty when the payload is valid). Checks:

- `email` if `shop_require_email` is `1`
- `first_name` if `shop_require_first_name` is `1`
- `terms` if `shop_require_terms` is `1`

### `prepareOrderData(array $data): array`

Maps the checkout form payload to the columns the Order model expects. Generates `order_reference_id` via `ORD-` + `crc32(time() . random_bytes(8))` and re-rolls if a collision is detected.

Returns the full order array ready for `order_manager->place_order()`.

### `processPayment(int $providerId, array &$orderData): array`

Encrypts the verify token, builds return/cancel/notify URLs, calls the payment driver. Mutates `$orderData` to inject `payment_verify_token`, `payment_provider`, and `payment_provider_id`. Returns the driver's response array verbatim.

### `markOrderAsPaid(int $orderId): void`

Sets `Order.is_paid = 1`, fires `OrderWasPaid($order)` + legacy `mw.cart.checkout.order_paid` event. Idempotent — calling twice for the same order is a no-op (early return if already paid).

### `confirmEmailSend(int $orderId, ?string $to = null, bool $noCache = false, bool $skipEnabledCheck = false): bool`

Renders + sends the `new_order` mail template. Returns `true` if `Mail::to()->send()` was called, `false` if `order_email_enabled` is `0` (unless `$skipEnabledCheck` is `true`).

### `getUserInfo(string $key = null): mixed`

Returns session-scoped checkout buyer info. With no key returns the whole array; with a key returns that field. Merges in (in priority order):

1. Logged-in user's profile.
2. Logged-in user's saved shipping address.
3. Session checkout array.

### `setUserInfo(string $key, mixed $value): void`

Writes one field into the session checkout array.

### `getShippingCost(array $data): float`

Wrapper around `shipping_method_manager->getShippingCost($providerId, $data)`. `$data` keys: `shipping_provider_id`, `country`, `city`, `zip`.

### `addItem(int $productId, int $quantity = 1): array`

Convenience wrapper around `shop_manager->add_to_cart()`. Returns the cart row.

### `unifyParams(array $params): array`

Maps legacy field names to the canonical ones so external callers can use either:

| Legacy | Canonical |
|---|---|
| `postal_code` | `zip` |
| `payment_gw` | `payment_provider` |
| `shipping_method_id` | `shipping_provider_id` |
| `payment_method_id` | `payment_provider_id` |

---

## PaymentService

`Modules\Checkout\Services\PaymentService` — 74 lines. Thin wrapper around the Payment module's provider registry.

### `initiatePayment(array $data): Payment`

Creates a `Payment` record (`rel_type='orders'`, `rel_id=$data['order_id']`, `status='pending'`), fetches the chosen provider, calls the driver's `initiatePayment()`, updates the payment row with the response (`transaction_id`, `status`, `response_data`), and returns the Payment model.

### `getAvailablePaymentMethods(): array`

Returns an array of active payment providers:

```php
[
  ['id' => 2, 'title' => 'Stripe', 'description' => '...', 'is_active' => 1, 'form_view' => 'modules.payment::stripe.form'],
  ...
]
```

---

## CheckoutManager (facade)

`Modules\Checkout\Repositories\CheckoutManager` — 295 lines. Container-bound as `checkout_manager`. Mostly a proxy to `CheckoutService`, plus legacy helpers.

```php
app('checkout_manager')->checkout($data);
app('checkout_manager')->getUserInfo('email');
app('checkout_manager')->setUserInfo('email', 'ada@example.com');
app('checkout_manager')->payment_options('stripe_publishable_key');
app('checkout_manager')->mark_order_as_paid(123);
app('checkout_manager')->confirm_email_send(123, 'ada@example.com');
app('checkout_manager')->getShippingModules();
app('checkout_manager')->getShippingCost(['shipping_provider_id' => 4, 'zip' => 'W1A 1AA']);
```

`payment_options($key)` is the legacy lookup for per-provider config (Stripe publishable key, PayPal client id, etc.) — it delegates to `payment_method_manager->getProviders()` and resolves the key from the matched provider's settings JSON.

---

## HTTP controllers

### `CheckoutApiController`

REST API. All routes prefixed `/api/module/checkout`, middleware `['api', 'throttle:public']`.

| Method | Path | Action | Returns |
|---|---|---|---|
| GET | `/` | `index()` | current session checkout state + cart summary |
| POST | `/` | `store()` | runs `checkout($data)`, returns full result array |
| PUT | `/` | `update()` | writes one field via `setUserInfo`, returns updated state |
| POST | `/validate` | `validate()` | returns validation errors for the partial payload |
| GET | `/shipping-methods` | `shippingMethods()` | `[ShippingProvider, ...]` |
| GET | `/payment-methods` | `paymentMethods()` | `[PaymentProvider with form_view, ...]` |
| POST | `/calculate-shipping` | `calculateShipping()` | `{ cost: float, currency: string }` |
| GET | `/order/{orderReferenceId}` | `orderStatus()` | `{ order_reference_id, order_status, is_paid, payment_status, amount }` |

### `CheckoutPaymentController`

Payment gateway callbacks. All routes prefixed `/api/checkout/payment`, middleware `api`.

| Method | Path | Action | Used by |
|---|---|---|---|
| GET | `/return` | `return(Request $request)` | gateway success redirect |
| GET | `/cancel` | `cancel(Request $request)` | gateway cancel redirect |
| POST | `/notify` | `notify(Request $request)` | gateway IPN webhook |

All three delegate to `handlePaymentResponse($request, $resultType)` which:

1. Resolves the order from `$request->order_reference_id`.
2. Decrypts the `payment_verify_hash`, compares to `payment_verify_token`.
3. Calls `payment_method_manager->verifyPayment($providerId, $request)`.
4. On success: `CheckoutService::markOrderAsPaid()` + creates the `Payment` record + fires `PaymentWasProcessed($payment)`.
5. Redirects to `/checkout/{success,cancelled,failed}` (the IPN webhook returns a 200 plain text instead of redirecting).

---

## Livewire components

### `CheckoutWizard`

`Modules\Checkout\Livewire\CheckoutWizard` (500+ lines). Mounted as `<livewire:checkout-wizard />` inside `CheckoutPage`.

Public properties (Livewire-accessible):

| Property | Type | Purpose |
|---|---|---|
| `$data` | array | Form state (merged into via Filament Schema binding) |
| `$step` | string | Current step key (`cart`, `contact`, `shipping`, `payment`, `review`) |
| `$cartItems` | array | Cart line items, refreshed on mount + after step transitions |
| `$cartTotal` | array | Cart totals (subtotal, shipping, tax, discount, total) |
| `$shippingMethods` | array | Available `ShippingProvider` array |
| `$paymentMethods` | array | Available `PaymentProvider` array |
| `$order` | Order\|null | Set after step 5 submits and the order is placed |

Public methods:

| Method | Purpose |
|---|---|
| `mount()` | Loads cart/shipping/payment + pre-fills contact form |
| `form(Schema $schema)` | Returns the Filament Wizard schema |
| `submitAction()` | Wired on the wizard's final step — runs `checkout($this->data)` |
| `loadCartData()` | Refreshes `$cartItems` + `$cartTotal` from `cart_manager->get_cart()` |
| `loadShippingMethods()` | Refreshes `$shippingMethods` from `shipping_method_manager->getProviders()` |
| `loadPaymentMethods()` | Refreshes `$paymentMethods` from `payment_method_manager->getProviders()` |

### `CartItems` (Livewire view component)

Reads the current cart and renders the line-item summary as used inside step 1 of the wizard. Lives at `Modules\Checkout\Livewire\CartItems`.

### `ReviewOrder` (Livewire view component)

Renders the final order summary inside step 5. Reads `$data`, `$cartItems`, `$cartTotal` from the parent wizard via Livewire props.

---

## Filament resources + pages

### `CheckoutResource`

`Modules\Checkout\Filament\Resources\CheckoutResource`. Owns no record model (Filament's `getModel()` returns a synthetic stub). Defines:

| Page class | Route | Purpose |
|---|---|---|
| `CheckoutPage` | `/checkout` | renders the wizard view |
| `CheckoutSuccessPage` | `/checkout/success` | post-paid landing |
| `CheckoutFailedPage` | `/checkout/failed` | gateway-rejected landing |
| `CheckoutCancelledPage` | `/checkout/cancelled` | user-cancelled landing |

The resource is registered on the **checkout** Filament panel (`FilamentCheckoutPanelProvider`), not on the admin panel.

---

## Middleware

### `CheckoutEmptyCart`

`Modules\Checkout\Http\Middleware\CheckoutEmptyCart`. Applied to the `/checkout` route.

- If `cart_manager->get_cart()` is empty → redirects to `content_repository->getFirstShopPage()` (fallback: site root).
- If `shop_require_registration === 1` and the user is not logged in → redirects to `/admin/login?redirect=/checkout`.

---

## Events

### `OrderWasPaid` *(from Order module)*

Fired by `CheckoutService::markOrderAsPaid()`. Constructor receives the `Order` model.

```php
public function __construct(public Order $order) {}
```

Typical listeners: inventory deduction, shipping label generation, accounting integrations, analytics push.

### `PaymentWasProcessed` *(from Payment module)*

Fired by `CheckoutPaymentController::handlePaymentResponse()` after the gateway verifies. Constructor receives the `Payment` model.

```php
public function __construct(public Payment $payment) {}
```

### Reserved Checkout-owned events *(empty, not yet dispatched)*

| Event | File | Reserved for |
|---|---|---|
| `BeginCheckoutEvent` | `Events/BeginCheckoutEvent.php` | wizard step 1 mount — future analytics |
| `AddShippingInfoEvent` | `Events/AddShippingInfoEvent.php` | wizard step 3 submit — future analytics |
| `AddPaymentInfoEvent` | `Events/AddPaymentInfoEvent.php` | wizard step 4 submit — future analytics |

These classes exist so the Google Analytics 4 + Meta Pixel events can be wired in a future ticket without breaking the API; listeners can already register, the fires will land later.

---

## Helper functions

Defined in `Modules\Checkout\Support\helpers.php`. Auto-loaded by `module.json → files`.

| Function | Purpose |
|---|---|
| `checkout(array $data): array` | `app('checkout_manager')->checkout($data)` |
| `checkout_url(): string` | Returns `/checkout` (respects multilanguage prefix) |
| `checkout_get_user_info(?string $key = null): mixed` | `getUserInfo()` |
| `checkout_set_user_info(string $key, mixed $value): void` | `setUserInfo()` |
| `checkout_ipn(): void` | Stub — registers the IPN endpoint at boot (legacy compat) |
| `checkout_confirm_email_test(array $params): bool` | `confirmEmailSend()` with `skipEnabledCheck = true` for QA |
