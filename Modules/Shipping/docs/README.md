# `Shipping` module

> **Slug:** `shipping`
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

### `shipping_providers` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `name` | `string` | nullable |
  | `provider` | `string` | nullable |
  | `is_active` | `integer` | nullable |
  | `position` | `integer` | nullable |
  | `settings` | `text` | nullable |
  | `timestamps` | `timestamps` | — |
  | `is_default` | `integer` | nullable |
  | `description` | `text` | nullable |
  | `icon` | `string` | nullable |
  | `is_default` | `dropColumn` | — |
  | `description` | `dropColumn` | — |
  | `icon` | `dropColumn` | — |

## Models

### `Modules\Shipping\Models\ShippingProvider`

Source: `Models/ShippingProvider.php`. 

**Fillable:** `id`, `name`, `provider`, `is_active`, `is_default`, `settings`, `position`

**Casts:**

  - `settings` → `array`

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `ShippingApiController::index` |
  | `GET` | `/{shipping}` | `ShippingApiController::show` |
  | `POST` | `/` | `ShippingApiController::store` |
  | `PUT` | `/{shipping}` | `ShippingApiController::update` |
  | `PATCH` | `/{shipping}` | `ShippingApiController::update` |
  | `DELETE` | `/{shipping}` | `ShippingApiController::destroy` |

## Controllers

### `Modules\Shipping\Http\Controllers\Api\ShippingApiController`

Source: `Http/Controllers/Api/ShippingApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Service classes

### `Modules\Shipping\Services\ShippingMethodManager`

Source: `Services/ShippingMethodManager.php`.

  - `getDefaultDriver()`
  - `driverExists($driver)`
  - `getDrivers()`
  - `getProviders(): array`
  - `getProviderById($providerId): ShippingProvider|null`
  - `hasProviders(): bool`
  - `getForm($providerId): array|null`
  - `getShippingCost($providerId, $data): float|int`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource` | Shop Settings | Shipping Providers |
  | `Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider` | — | — |
  | `Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\EditShippingProvider` | — | — |
  | `Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Shipping/Tests`

### `Tests/Filament/ShippingResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Drivers/FlatRateTest.php`

  - `it_defaultshippingcost`

### `Tests/Unit/Drivers/PickupFromAddressTest.php`

  - `it_defaultaddress`

### `Tests/Unit/Drivers/ShippingToCountryTest.php`

  - `it_defaultcountryrates`

### `Tests/Unit/Drivers/WeightBasedTest.php`

  - `it_default_cost_without_model`

### `Tests/Unit/Filament/ShippingProviderResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_delete_action_removes_record`

## Service providers

  - `Modules\Shipping\Providers\ShippingServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
