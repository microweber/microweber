# `Payment` module

> **Slug:** `payment`
> **Tier:** 1
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

### `payment_providers` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `name` | `string` | nullable |
  | `provider` | `string` | nullable |
  | `is_active` | `integer` | nullable |
  | `is_default` | `integer` | nullable |
  | `position` | `integer` | nullable |
  | `settings` | `text` | nullable |
  | `timestamps` | `timestamps` | — |

### `payments` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `rel_id` | `string` | nullable |
  | `rel_type` | `string` | nullable |
  | `amount` | `decimal` | nullable |
  | `currency` | `string` | nullable |
  | `status` | `string` | nullable |
  | `payment_provider` | `string` | nullable |
  | `payment_provider_id` | `string` | nullable |
  | `transaction_id` | `string` | nullable |
  | `payment_data` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Payment\Models\Payment`

Source: `Models/Payment.php`. 

**Fillable:** `rel_id`, `rel_type`, `amount`, `currency`, `status`, `payment_provider`, `transaction_id`, `payment_provider_id`, `payment_data`

**Casts:**

  - `payment_data` → `array`

### `Modules\Payment\Models\PaymentProvider`

Source: `Models/PaymentProvider.php`. 

**Fillable:** `id`, `name`, `provider`, `is_active`, `is_default`, `settings`, `position`

**Casts:**

  - `settings` → `array`

## API endpoints

### `routes/webhooks.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `POST` | `payment/stripe/webhook` | `StripeWebhookController::handleWebhook` |
  | `POST` | `payment/paypal/webhook` | `PayPalWebhookController::handleWebhook` |

## Controllers

### `Modules\Payment\Http\Controllers\PayPalWebhookController`

Source: `Http/Controllers/PayPalWebhookController.php`.

  - `handleWebhook(Request $request)`

### `Modules\Payment\Http\Controllers\StripeWebhookController`

Source: `Http/Controllers/StripeWebhookController.php`.

  - `handleWebhook(Request $request)`

## Service classes

### `Modules\Payment\Services\PaymentMethodManager`

Source: `Services/PaymentMethodManager.php`.

  - `getDefaultDriver()`
  - `driverExists($driver)`
  - `getDrivers()`
  - `getProviders(): array`
  - `getProviderById($providerId): PaymentProvider|null`
  - `hasProviders(): bool`
  - `getForm($providerId): array|null`
  - `process($providerId, $data): array|null`
  - `verifyPayment($providerId, $data): array|null`

## Events

  - `Modules\Payment\Events\PaymentWasCreated`
  - `Modules\Payment\Events\PaymentWasDeleted`
  - `Modules\Payment\Events\PaymentWasProcessed`
  - `Modules\Payment\Events\PaymentWasUpdated`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Payment\Filament\Admin\Resources\PaymentProviderResource` | Shop Settings | Configure your shop payments settings |
  | `Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider` | — | — |
  | `Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\EditPaymentProvider` | — | — |
  | `Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders` | — | — |
  | `Modules\Payment\Filament\Admin\Resources\PaymentResource` | Shop Settings | — |
  | `Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\CreatePayment` | — | — |
  | `Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\EditPayment` | — | — |
  | `Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\ListPayments` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Payment/Tests`

### `Tests/Feature/PayPalWebhookTest.php`

  - `test_webhook_endpoint_is_accessible_without_csrf`
  - `test_webhook_handles_invalid_payload`
  - `test_webhook_processes_payment_capture_completed`
  - `test_webhook_handles_checkout_order_completed`
  - `test_webhook_handles_checkout_order_approved`
  - `test_webhook_handles_missing_order`
  - `test_webhook_handles_payment_capture_denied`
  - `test_webhook_processes_payment_capture_refunded`
  - `test_webhook_handles_customer_dispute_created`
  - `test_webhook_logs_unhandled_events`
  - …1 more.

### `Tests/Feature/StripeWebhookTest.php`

  - `test_webhook_endpoint_is_accessible_without_csrf`
  - `test_webhook_handles_invalid_payload`
  - `test_webhook_processes_checkout_session_completed`
  - `test_webhook_handles_checkout_session_not_paid`
  - `test_webhook_handles_missing_order`
  - `test_webhook_processes_payment_intent_succeeded`
  - `test_webhook_handles_payment_intent_payment_failed`
  - `test_webhook_handles_payment_intent_canceled`
  - `test_webhook_handles_charge_refunded`
  - `test_webhook_logs_unhandled_events`

### `Tests/Filament/PaymentResourceTest.php`

  - `it_can_render_payment_providers_list_page`

### `Tests/Unit/Filament/PaymentProviderResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_delete_action_removes_record`

### `Tests/Unit/Filament/PaymentResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_status_badge_displays_correctly`

### `Tests/Unit/PayPalDriverTest.php`

  - `test_paypal_driver_has_correct_provider_name`
  - `test_paypal_driver_returns_title`
  - `test_paypal_driver_returns_logo`
  - `test_paypal_driver_returns_settings_form`
  - `test_paypal_driver_returns_payment_form`
  - `test_paypal_driver_gets_webhook_id`
  - `test_paypal_driver_returns_null_webhook_id_when_not_set`
  - `test_paypal_driver_processes_payment_with_rest_credentials`
  - `test_paypal_driver_processes_payment_with_classic_credentials`
  - `test_paypal_driver_fails_without_credentials`
  - …2 more.

### `Tests/Unit/PaymentMethodManagerTest.php`

  - `it_driver_resolution`

## Service providers

  - `Modules\Payment\Providers\PaymentServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
