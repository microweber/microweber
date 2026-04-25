# `Shipping` module

> **Slug:** `shipping`
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

Migrations under `Modules/Shipping/database/migrations/`:

  - `database/migrations/2024_07_18_00001_create_shipping_providers_table.php`
  - `database/migrations/2026_03_21_000001_add_weight_based_columns_to_shipping_providers.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Shipping\Models\ShippingProvider` | `Models/ShippingProvider.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Shipping\Http\Controllers\Api\ShippingApiController`

## Service classes

  - `Modules\Shipping\Services\ShippingMethodManager`

## Filament admin

  - `Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource`
  - `Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\CreateShippingProvider`
  - `Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\EditShippingProvider`
  - `Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders`

## Tests

Run: `php vendor/bin/phpunit Modules/Shipping/Tests`

Test files:

  - `Tests/Filament/ShippingResourceTest.php`
  - `Tests/Unit/Drivers/FlatRateTest.php`
  - `Tests/Unit/Drivers/PickupFromAddressTest.php`
  - `Tests/Unit/Drivers/ShippingToCountryTest.php`
  - `Tests/Unit/Drivers/WeightBasedTest.php`
  - `Tests/Unit/Filament/ShippingProviderResourceTest.php`
  - `Tests/Unit/FlatRateFilamentResourceTest.php`
  - `Tests/Unit/PickupFromAddressFilamentResourceTest.php`
  - `Tests/Unit/ShippingManagerTest.php`

## Service providers

  - `Modules\Shipping\Providers\ShippingServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
