# Checkout Module

The Checkout module is the **conversion flow** of the e-commerce sub-cluster. It owns the multi-step wizard that turns the in-progress [Cart](/modules/cart/) state into a finalised [Order](/modules/order/) (when that module ships) and reconciles the response from the chosen payment gateway.

> **TL;DR** — Checkout is a 5-step Livewire wizard (cart → contact → shipping → payment → review) on top of [`CheckoutService`](./api.md#checkoutservice), which validates the cart, builds the `cart_orders` row via `order_manager->place_order()`, dispatches to the payment gateway, listens for `return` / `notify` / `cancel` callbacks, and fires `OrderWasPaid` so downstream modules (inventory, email) react.

---

## What this module owns

| Concern | Storage / surface |
|---|---|
| Multi-step wizard form | `Livewire\CheckoutWizard` + Filament `CheckoutResource` pages |
| Checkout orchestration | `Services\CheckoutService` (537 lines) |
| Public REST API | `/api/module/checkout/*` |
| Payment gateway round-trip URLs | `/api/checkout/payment/{return,cancel,notify}` |
| Cart → Order conversion contract | `CheckoutService::prepareOrderData()` |
| Order confirmation email | `CheckoutService::confirmEmailSend()` |
| Session-scoped buyer info | `CheckoutService::getUserInfo()` / `setUserInfo()` |
| Empty-cart / registration guards | `Http\Middleware\CheckoutEmptyCart` |
| Filament checkout success / failed / cancelled pages | `Filament\Resources\CheckoutResource\Pages` |

What this module does **NOT** own:

- The cart line items, totals, coupons, tax → [Cart module](/modules/cart/)
- The `cart_orders` table schema and Eloquent model → Order module
- Payment provider registry + driver lifecycle → Payment module (Checkout calls `payment_method_manager`)
- Shipping cost calculation → Shipping module (Checkout calls `shipping_method_manager`)
- Tax rules → Tax / Cart modules (Checkout reads cached tax via `cart_get_tax()`)
- Mail template rendering → MailTemplate module (Checkout calls `MailTemplateService::createMailable()`)
- Inventory deduction on payment → Order / Product modules (listening to `OrderWasPaid`)

---

## Architectural fact: zero owned models, full delegation

Checkout owns **no Eloquent models**. Every record it touches lives in another module:

- The order row is `Order` from the Order module — Checkout populates it via `order_manager->place_order($orderData)`.
- The cart rows are `Cart` from the Cart module — Checkout reads them through `cart_manager->get_cart()` or `CartService::getCart()`.
- The payment record is `Payment` from the Payment module — created by Checkout with `rel_type='orders'` and `rel_id=$order->id`.
- Shipping providers and payment providers are `ShippingProvider` / `PaymentProvider` from their respective modules — queried read-only by ID.

This means Checkout is a **pure orchestrator**. It can be ripped out, rewritten in another framework, or replaced wholesale without touching any storage. Every state change is a method call on another module's facade.

---

## The 5-step wizard

`Livewire\CheckoutWizard::form()` returns a Filament `Wizard::make()->steps(...)` schema with five steps:

| # | Step key | Validates | Notable fields |
|---|---|---|---|
| 1 | `cart` | non-empty cart | line-item summary (view component) |
| 2 | `contact` | buyer identity | first_name, last_name, email, phone, country, city, state, zip, address; optional `create_account` toggle with password fields |
| 3 | `shipping` | shipping_provider_id | radio of `shippingMethods[]` with live cost calculation |
| 4 | `payment` | payment_provider_id | radio of `paymentMethods[]` + dynamic provider-specific form |
| 5 | `review` | full data integrity | ReviewOrder Livewire component shows the final summary |

On step 5 submit → `checkout()` helper → `CheckoutService::checkout()` runs the full pipeline.

The wizard's `step` is mirrored to a URL query param so refresh + back-button preserve the user's position.

---

## The checkout pipeline (`CheckoutService::checkout`)

```
1. validateCart()              ← non-empty + valid line items
2. unifyParams()               ← maps legacy field names (postal_code→zip, payment_gw→payment_provider, ...)
3. validateCheckoutData()      ← checks required fields against the website options (email/first_name/terms)
4. prepareOrderData()          ← maps form → cart_orders columns + generates order_reference_id (ORD-<crc32>)
5. processPayment()            ← encrypts verify_token, builds return/cancel/notify URLs, calls payment_method_manager->process()
6. order_manager->place_order()← creates the cart_orders row, returns order_id
7. updateQuantities()          ← deducts stock if is_paid === 0 (otherwise OrderWasPaid listener handles it)
8. confirmEmailSend()          ← renders order confirmation email via MailTemplateService
9. markOrderAsPaid() (if applicable) → fires OrderWasPaid + legacy mw.cart.checkout.order_paid
```

The return payload includes `order_id`, `order_completed`, `is_paid`, `transaction_id`, and (when the payment provider redirects) a `redirectUrl` so the frontend can hand off to the gateway.

---

## Guest vs registered checkout

Both flows share the same wizard. The contact step has a `create_account` toggle:

- **Guest checkout** — `create_account = false` → only the order row is created; the email lands as a "guest order" reachable via `order_reference_id`.
- **Registered guest** — `create_account = true` → password + password_confirmation fields appear; on submit, a real user is provisioned and the order is linked to them.
- **Logged-in user** — contact fields are pre-filled from `user_manager->session_id()` + the user's shipping address; the order is linked to the user automatically.

The `shop_require_registration` website option (from the Admin module) flips this from "guest allowed" to "must log in first". The `CheckoutEmptyCart` middleware enforces it.

---

## Payment gateway round-trip

Gateways that redirect (Stripe Checkout, PayPal, etc.) need three return URLs. Checkout builds them server-side and embeds an encrypted `payment_verify_token`:

| URL | Method | Purpose |
|---|---|---|
| `/api/checkout/payment/return` | GET | Buyer is redirected back after a successful payment — Checkout verifies the gateway response and marks the order paid. |
| `/api/checkout/payment/cancel` | GET | Buyer cancelled at the gateway — Checkout marks the order cancelled and redirects to the checkout-cancelled page. |
| `/api/checkout/payment/notify` | POST | Server-to-server webhook (IPN) — Checkout verifies the payload (signature, token) and updates the order asynchronously. |

`CheckoutPaymentController::handlePaymentResponse()` is the single entry point all three use; it validates the token, calls `payment_method_manager->verifyPayment()`, fires `PaymentWasProcessed`, and (on success) `OrderWasPaid`.

---

## Events fired

| Event | Where | Listeners (typical) |
|---|---|---|
| `OrderWasPaid($order)` | `CheckoutService::markOrderAsPaid()` after `is_paid` flips to 1 | inventory deduction (Order/Product), shipping label trigger (Shipping), accounting hooks |
| `PaymentWasProcessed($payment)` | `CheckoutPaymentController::handlePaymentResponse()` after gateway verification | analytics, accounting, audit log |
| Legacy `mw.cart.checkout.order_paid` | fired alongside `OrderWasPaid` | older listeners not yet migrated |

There are also empty event classes (`BeginCheckoutEvent`, `AddShippingInfoEvent`, `AddPaymentInfoEvent`) defined in `Events/` that are reserved for future analytics integration but are not yet dispatched internally.

---

## Surfaces

| Surface | Where | Audience |
|---|---|---|
| Public `/checkout` Filament page | `CheckoutResource\Pages\CheckoutPage` → `modules.checkout::filament.pages.checkout-wizard-page` | end customers |
| `/checkout/success`, `/checkout/failed`, `/checkout/cancelled` | `CheckoutResource\Pages\Checkout{Success,Failed,Cancelled}Page` | end customers post-payment |
| REST API | `/api/module/checkout/*` (CheckoutApiController) | SPA clients, mobile apps |
| Legacy webhook routes | `/api/checkout/payment/{return,cancel,notify}` (CheckoutPaymentController) | payment gateways |
| Helpers | `checkout()`, `checkout_get_user_info()`, `checkout_set_user_info()`, `checkout_confirm_email_test()` in `Support/helpers.php` | blade templates, custom flows |

---

## Where to next

- [Installation](./installation.md) — service provider, route loading, panel registration.
- [Usage](./usage.md) — running the wizard, handling guest vs registered, hooking into the post-paid pipeline.
- [API](./api.md) — `CheckoutService`, `PaymentService`, `CheckoutManager`, controllers, events.
- [Examples](./examples.md) — full end-to-end programmatic checkout, custom shipping method registration, payment-gateway return-URL handling.
- [Troubleshooting](./troubleshooting.md) — empty cart redirect loops, payment token mismatches, mail template not firing.
