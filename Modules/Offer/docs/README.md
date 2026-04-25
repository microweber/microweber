# `Offer` module

> **Slug:** `offer`
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

### `offers` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `product_id` | `integer` | nullable |
  | `price_id` | `integer` | nullable |
  | `offer_price` | `float` | nullable |
  | `expires_at` | `datetime` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |
  | `is_active` | `integer` | nullable |
  | `created_at` | `datetime` | nullable |
  | `updated_at` | `datetime` | nullable |

## Models

### `Modules\Offer\Models\Offer`

Source: `Models/Offer.php`. 

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/api.php`

## Controllers

### `Modules\Offer\Http\Controllers\Api\OfferApiController`

Source: `Http/Controllers/Api/OfferApiController.php`.

  - `index()`
  - `getByProductId($productId = false)`
  - `searchProducts(Request $request)`

### `Modules\Offer\Http\Controllers\Api\OfferApiResourceController`

Source: `Http/Controllers/Api/OfferApiResourceController.php`.

  - `store(OfferCreateUpdateRequest $request)`
  - `destroy(Request $request)`

## Events

  - `Modules\Offer\Listeners\AddSpecialPriceProductListener`
  - `Modules\Offer\Listeners\EditSpecialPriceProductListener`
  - `Modules\Offer\Listeners\ModifySpecialPriceProductTrait`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Offer\Filament\Admin\Resources\OfferResource` | Shop Settings | — |
  | `Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\CreateOffer` | — | — |
  | `Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\EditOffer` | — | — |
  | `Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\ListOffers` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Offer/Tests`

### `Tests/Filament/OfferResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/OfferResourceTest.php`

  - `it_index_page_shows_records`
  - `it_edit_page_loads`
  - `it_pages_exist`

## Service providers

  - `Modules\Offer\Providers\OfferEventServiceProvider`
  - `Modules\Offer\Providers\OfferServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
