# `Tax` module

> **Slug:** `tax`
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

### `tax_types` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `name` | `string` | nullable |
  | `type` | `string` | nullable |
  | `description` | `text` | nullable |
  | `settings` | `text` | nullable |
  | `rate` | `decimal` | nullable |
  | `compound_tax` | `decimal` | nullable |
  | `collective_tax` | `decimal` | nullable |
  | `timestamps` | `timestamps` | — |

### `tax_rates` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | — |
  | `description` | `text` | nullable |
  | `country_code` | `string` | nullable, indexed |
  | `state_code` | `string` | nullable, indexed |
  | `zip_code_pattern` | `string` | nullable |
  | `city` | `string` | nullable |
  | `type` | `enum` | has-default |
  | `rate` | `decimal` | has-default |
  | `compound_tax` | `boolean` | has-default |
  | `priority` | `integer` | indexed, has-default |
  | `is_default` | `boolean` | has-default |
  | `is_active` | `boolean` | indexed, has-default |
  | `valid_from` | `timestamp` | nullable |
  | `valid_until` | `timestamp` | nullable |
  | `applies_to_products` | `json` | nullable |
  | `applies_to_categories` | `json` | nullable |
  | `applies_to_customer_groups` | `json` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Tax\Models\TaxRate`

Source: `Models/TaxRate.php`. Table: `tax_rates`. 

**Fillable:** `name`, `description`, `country_code`, `state_code`, `zip_code_pattern`, `city`, `type`, `rate`, `compound_tax`, `priority`, `is_default`, `is_active`, `valid_from`, `valid_until`, `applies_to_products`, `applies_to_categories`, `applies_to_customer_groups`

**Casts:**

  - `rate` → `decimal:4`
  - `compound_tax` → `boolean`
  - `is_default` → `boolean`
  - `is_active` → `boolean`
  - `valid_from` → `datetime`
  - `valid_until` → `datetime`
  - `applies_to_products` → `array`
  - `applies_to_categories` → `array`
  - `applies_to_customer_groups` → `array`

### `Modules\Tax\Models\TaxType`

Source: `Models/TaxType.php`. Table: `tax_types`. 

**Fillable:** `id`, `name`, `type`, `rate`, `description`, `settings`

**Casts:**

  - `percent` → `float`
  - `settings` → `array`

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `TaxApiController::index` |
  | `GET` | `/{tax}` | `TaxApiController::show` |
  | `POST` | `/` | `TaxApiController::store` |
  | `PUT` | `/{tax}` | `TaxApiController::update` |
  | `PATCH` | `/{tax}` | `TaxApiController::update` |
  | `DELETE` | `/{tax}` | `TaxApiController::destroy` |

## Controllers

### `Modules\Tax\Http\Controllers\Api\TaxApiController`

Source: `Http/Controllers/Api/TaxApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Service classes

### `Modules\Tax\Services\TaxCalculator`

Source: `Services/TaxCalculator.php`.

  - `calculate(float $amount, array $location = [], array $context = []): array`
  - `calculateAmount(float $amount, array $location = [], array $context = []): float`
  - `getApplicableTaxRates(array $location = [], array $context = []): array`
  - `isEnabled(): bool`
  - `enable(): void`
  - `disable(): void`
  - `validateLocation(array $location): array`
  - `getTaxSummary(float $amount, array $location = [], array $context = []): array`
  - `clearCache(): void`

### `Modules\Tax\Services\TaxManager`

Source: `Services/TaxManager.php`.

  - `get($params = array()`
  - `save($params = array()`
  - `delete_by_id($data)`
  - `calculate($sum, $is_gross = false)`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Tax\Filament\Admin\Resources\TaxRateResource` | Shop Settings | Tax Rates |
  | `Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\CreateTaxRate` | — | — |
  | `Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\EditTaxRate` | — | — |
  | `Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\ListTaxRates` | — | — |
  | `Modules\Tax\Filament\Admin\Resources\TaxResource` | Shop Settings | — |
  | `Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\CreateTax` | — | — |
  | `Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\EditTax` | — | — |
  | `Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\ListTaxes` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Tax/Tests`

### `Tests/Filament/TaxResourceTest.php`

  - `it_can_render_tax_rates_list_page`

### `Tests/TaxResourceTest.php`

  - `it_submits_the_tax_form`

## Service providers

  - `Modules\Tax\Providers\TaxServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
