# `Coupons` module

> **Slug:** `coupons`
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

Migrations under `Modules/Coupons/database/migrations/`:

  - `database/migrations/2023_00_00_000001_create_cart_coupons_log_table.php`
  - `database/migrations/2023_00_00_000001_create_cart_coupons_table.php`
  - `database/migrations/2024_01_10_000001_create_coupons_tables.php`
  - `database/migrations/2025_03_22_000001_add_advanced_coupon_fields.php`
  - `database/migrations/2025_03_22_000002_add_advanced_discount_rules.php`
  - `database/migrations/2025_04_04_132753_add_valid_dates_to_coupons_table.php`
  - `database/migrations/2025_04_04_140101_add_product_restrictions_to_coupons.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Coupons\Models\CartCoupon` | `Models/CartCoupon.php` |
| `Modules\Coupons\Models\CartCouponLog` | `Models/CartCouponLog.php` |
| `Modules\Coupons\Models\Coupon` | `Models/Coupon.php` |
| `Modules\Coupons\Models\CouponLog` | `Models/CouponLog.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Coupons\Http\Controllers\Api\CouponsApiController`

## Service classes

  - `Modules\Coupons\Services\CouponService`

## Events

  - `Modules\Coupons\Listeners\OrderWasCreatedCouponCodeLogger`

## Filament admin

  - `Modules\Coupons\Filament\Resources\CouponResource`
  - `Modules\Coupons\Filament\Resources\CouponResource\Pages\CreateCoupon`
  - `Modules\Coupons\Filament\Resources\CouponResource\Pages\EditCoupon`
  - `Modules\Coupons\Filament\Resources\CouponResource\Pages\ListCoupons`
  - `Modules\Coupons\Filament\Resources\CouponResource\RelationManagers\LogsRelationManager`

## Tests

Run: `php vendor/bin/phpunit Modules/Coupons/Tests`

Test files:

  - `Tests/Filament/CouponResourceTest.php`
  - `Tests/Unit/AdvancedDiscountRulesTest.php`
  - `Tests/Unit/CouponAdvancedRulesTest.php`
  - `Tests/Unit/CouponApplyTest.php`
  - `Tests/Unit/CouponServiceTest.php`
  - `Tests/Unit/CouponTestCase.php`
  - `Tests/Unit/CouponValidationTest.php`
  - `Tests/Unit/Filament/CouponResourceTest.php`
  - `Tests/Unit/Livewire/CouponsTest.php`

## Service providers

  - `Modules\Coupons\Providers\CouponOrderEventServiceProvider`
  - `Modules\Coupons\Providers\CouponsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
