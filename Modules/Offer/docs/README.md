# `Offer` module

> **Slug:** `offer`
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

Migrations under `Modules/Offer/database/migrations/`:

  - `database/migrations/2020_00_00_000000_create_offers_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Offer\Models\Offer` | `Models/Offer.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Offer\Http\Controllers\Api\OfferApiController`
  - `Modules\Offer\Http\Controllers\Api\OfferApiResourceController`

## Events

  - `Modules\Offer\Listeners\AddSpecialPriceProductListener`
  - `Modules\Offer\Listeners\EditSpecialPriceProductListener`
  - `Modules\Offer\Listeners\ModifySpecialPriceProductTrait`

## Filament admin

  - `Modules\Offer\Filament\Admin\Resources\OfferResource`
  - `Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\CreateOffer`
  - `Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\EditOffer`
  - `Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\ListOffers`

## Tests

Run: `php vendor/bin/phpunit Modules/Offer/Tests`

Test files:

  - `Tests/Filament/OfferResourceTest.php`
  - `Tests/Unit/Filament/OfferResourceTest.php`
  - `Tests/Unit/OfferModelTest.php`
  - `Tests/Unit/OffersControllerTest.php`

## Service providers

  - `Modules\Offer\Providers\OfferEventServiceProvider`
  - `Modules\Offer\Providers\OfferServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
