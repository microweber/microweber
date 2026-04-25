# `Cart` module

> **Slug:** `cart`
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

Migrations under `Modules/Cart/database/migrations/`:

  - `database/migrations/2024_11_20_000001_create_cart_table.php`
  - `database/migrations/2026_03_23_000001_add_indexes_to_cart.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Cart\Models\Cart` | `Models/Cart.php` |
| `Modules\Cart\Models\ModelFilters\CartFilter` | `Models/ModelFilters/CartFilter.php` |
| `Modules\Cart\Models\UserCart` | `Models/UserCart.php` |

## API endpoints

Route files:

  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Cart\Http\Controllers\Api\CartApiController`
  - `Modules\Cart\Http\Controllers\CartApiController`

## Service classes

  - `Modules\Cart\Services\CartCouponService`
  - `Modules\Cart\Services\CartService`
  - `Modules\Cart\Services\CartTotalsService`

## Events

  - `Modules\Cart\Events\AddToCartEvent`
  - `Modules\Cart\Events\RemoveFromCartEvent`
  - `Modules\Cart\Listeners\UserLoginListener`

## Filament admin

  - `Modules\Cart\Filament\CartAddModuleSettings`

## Tests

Run: `php vendor/bin/phpunit Modules/Cart/Tests`

Test files:

  - `Tests/Unit/CartApiControllerTest.php`
  - `Tests/Unit/CartCouponServiceTest.php`
  - `Tests/Unit/CartModelTest.php`
  - `Tests/Unit/CartRepositoryTest.php`
  - `Tests/Unit/CartTest.php`
  - `Tests/Unit/CartTotalsServiceTest.php`
  - `Tests/Unit/UserCartTest.php`

## Service providers

  - `Modules\Cart\Providers\CartEventServiceProvider`
  - `Modules\Cart\Providers\CartServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
