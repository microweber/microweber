# `Checkout` module

> **Slug:** `checkout`
> **Tier:** 2
>
> Tier-2 module — service / API surface on top of shared infrastructure.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

This module owns no migrations of its own.

## API endpoints

Route files:

  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Checkout\Http\Controllers\Api\CheckoutApiController`
  - `Modules\Checkout\Http\Controllers\CheckoutPaymentController`

## Service classes

  - `Modules\Checkout\Services\CheckoutService`
  - `Modules\Checkout\Services\PaymentService`

## Events

  - `Modules\Checkout\Events\AddPaymentInfoEvent`
  - `Modules\Checkout\Events\AddShippingInfoEvent`
  - `Modules\Checkout\Events\BeginCheckoutEvent`

## Filament admin

  - `Modules\Checkout\Filament\Resources\CheckoutResource`
  - `Modules\Checkout\Filament\Resources\Pages\CheckoutCancelledPage`
  - `Modules\Checkout\Filament\Resources\Pages\CheckoutFailedPage`
  - `Modules\Checkout\Filament\Resources\Pages\CheckoutPage`
  - `Modules\Checkout\Filament\Resources\Pages\CheckoutSuccessPage`

## Tests

Run: `php vendor/bin/phpunit Modules/Checkout/Tests`

Test files:

  - `Tests/Feature/CheckoutCompleteEndToEndTest.php`
  - `Tests/Feature/CheckoutWizardEndToEndTest.php`
  - `Tests/Unit/CheckoutControllerTest.php`
  - `Tests/Unit/Filament/CheckoutResourceTest.php`
  - `Tests/Unit/Livewire/CheckoutWizardTest.php`

## Service providers

  - `Modules\Checkout\Providers\CheckoutServiceProvider`
  - `Modules\Checkout\Providers\FilamentCheckoutPanelProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
