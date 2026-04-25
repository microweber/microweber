# `Checkout` module

> **Slug:** `checkout`
> **Tier:** 2
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

This module owns no migrations of its own.

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `CheckoutApiController::index` |
  | `POST` | `/` | `CheckoutApiController::store` |
  | `PUT` | `/` | `CheckoutApiController::update` |
  | `POST` | `/validate` | `CheckoutApiController::validate` |
  | `GET` | `/shipping-methods` | `CheckoutApiController::shippingMethods` |
  | `GET` | `/payment-methods` | `CheckoutApiController::paymentMethods` |
  | `POST` | `/calculate-shipping` | `CheckoutApiController::calculateShipping` |
  | `GET` | `/order/{orderReferenceId}` | `CheckoutApiController::orderStatus` |

### `routes/web.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `api/checkout/payment/return` | `CheckoutPaymentController::return` |
  | `GET` | `api/checkout/payment/cancel` | `CheckoutPaymentController::cancel` |
  | `POST` | `api/checkout/payment/notify` | `CheckoutPaymentController::notify` |

## Controllers

### `Modules\Checkout\Http\Controllers\Api\CheckoutApiController`

Source: `Http/Controllers/Api/CheckoutApiController.php`.

  - `index(Request $request): JsonResponse`
  - `store(Request $request): JsonResponse`
  - `update(Request $request): JsonResponse`
  - `validate(Request $request): JsonResponse`
  - `shippingMethods(): JsonResponse`
  - `paymentMethods(): JsonResponse`
  - `calculateShipping(Request $request): JsonResponse`
  - `orderStatus(string $orderReferenceId): JsonResponse`

### `Modules\Checkout\Http\Controllers\CheckoutPaymentController`

Source: `Http/Controllers/CheckoutPaymentController.php`.

  - `return(Request $request)`
  - `cancel(Request $request)`
  - `notify(Request $request)`

## Service classes

### `Modules\Checkout\Services\CheckoutService`

Source: `Services/CheckoutService.php`.

  - `addItem($product, $quantity)`
  - `checkout(array $data)`
  - `setUserInfo($key, $value): void`
  - `getUserInfo($key = false)`
  - `confirmEmailSend($order_id, $to = false, $no_cache = true, $skip_enabled_check = false): bool`
  - `markOrderAsPaid($orderId)`
  - `updateQuantities($orderId)`
  - `getShippingCost(array $data = [])`

### `Modules\Checkout\Services\PaymentService`

Source: `Services/PaymentService.php`.

  - `initiatePayment(array $data)`
  - `getAvailablePaymentMethods()`

## Events

  - `Modules\Checkout\Events\AddPaymentInfoEvent`
  - `Modules\Checkout\Events\AddShippingInfoEvent`
  - `Modules\Checkout\Events\BeginCheckoutEvent`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Checkout\Filament\Resources\CheckoutResource` | — | Checkout |
  | `Modules\Checkout\Filament\Resources\Pages\CheckoutCancelledPage` | — | — |
  | `Modules\Checkout\Filament\Resources\Pages\CheckoutFailedPage` | — | — |
  | `Modules\Checkout\Filament\Resources\Pages\CheckoutPage` | — | — |
  | `Modules\Checkout\Filament\Resources\Pages\CheckoutSuccessPage` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Checkout/Tests`

### `Tests/Unit/Filament/CheckoutResourceTest.php`

  - `it_form_contains_personal_info_section`
  - `it_form_contains_payment_method_section`

### `Tests/Unit/Livewire/CheckoutWizardTest.php`

  - `it_has_five_wizard_steps`
  - `it_displays_shipping_methods_in_third_step`
  - `it_shows_order_review_in_final_step`
  - `it_validates_email_format`
  - `it_redirects_when_cart_is_empty`
  - `it_displays_order_summary_with_shipping_costs`
  - `it_requires_terms_acceptance_when_enabled`

## Service providers

  - `Modules\Checkout\Providers\CheckoutServiceProvider`
  - `Modules\Checkout\Providers\FilamentCheckoutPanelProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
