# `Payment` module

> **Slug:** `payment`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
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

Migrations under `Modules/Payment/database/migrations/`:

  - `database/migrations/2024_07_23_00001_create_payment_providers_table.php`
  - `database/migrations/2024_07_25_00007_create_payments_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Payment\Models\Payment` | `Models/Payment.php` |
| `Modules\Payment\Models\PaymentProvider` | `Models/PaymentProvider.php` |

## API endpoints

Route files:

  - `routes/webhooks.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Payment\Http\Controllers\PayPalWebhookController`
  - `Modules\Payment\Http\Controllers\StripeWebhookController`

## Service classes

  - `Modules\Payment\Services\PaymentMethodManager`

## Events

  - `Modules\Payment\Events\PaymentWasCreated`
  - `Modules\Payment\Events\PaymentWasDeleted`
  - `Modules\Payment\Events\PaymentWasProcessed`
  - `Modules\Payment\Events\PaymentWasUpdated`

## Filament admin

  - `Modules\Payment\Filament\Admin\Resources\PaymentProviderResource`
  - `Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider`
  - `Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\EditPaymentProvider`
  - `Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders`
  - `Modules\Payment\Filament\Admin\Resources\PaymentResource`
  - `Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\CreatePayment`
  - `Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\EditPayment`
  - `Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\ListPayments`

## Tests

Run: `php vendor/bin/phpunit Modules/Payment/Tests`

Test files:

  - `Tests/Feature/PayPalWebhookTest.php`
  - `Tests/Feature/StripeWebhookTest.php`
  - `Tests/Filament/PaymentResourceTest.php`
  - `Tests/Unit/Filament/PaymentProviderResourceTest.php`
  - `Tests/Unit/Filament/PaymentResourceTest.php`
  - `Tests/Unit/PayPalDriverTest.php`
  - `Tests/Unit/PaymentMethodManagerTest.php`
  - `Tests/Unit/PaymentModelTest.php`
  - `Tests/Unit/PaymentProviderDriversTest.php`
  - `Tests/Unit/PaymentProviderResourceTest.php`

## Service providers

  - `Modules\Payment\Providers\PaymentServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
