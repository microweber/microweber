# `Category` module

> **Slug:** `category`
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

Migrations under `Modules/Category/database/migrations/`:

  - `database/migrations/2024_11_20_000001_create_categories_table.php`
  - `database/migrations/2024_11_20_000002_create_categories_items_table.php`
  - `database/migrations/2026_03_23_000001_add_indexes_to_categories.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Category\Models\Category` | `Models/Category.php` |
| `Modules\Category\Models\CategoryItem` | `Models/CategoryItem.php` |
| `Modules\Category\Models\ModelFilters\CategoryFilter` | `Models/ModelFilters/CategoryFilter.php` |
| `Modules\Category\Models\ModelFilters\Traits\FilterByAvailableProductsByCategoryTrait` | `Models/ModelFilters/Traits/FilterByAvailableProductsByCategoryTrait.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Category\Http\Controllers\Api\CategoriesApiController`
  - `Modules\Category\Http\Controllers\Api\CategoryApiController`

## Filament admin

  - `Modules\Category\Filament\Admin\Resources\CategoryResource`
  - `Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory`
  - `Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\EditCategory`
  - `Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories`
  - `Modules\Category\Filament\Admin\Resources\ShopCategoryResource`
  - `Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\CreateShopCategory`
  - `Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\EditShopCategory`
  - `Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories`
  - `Modules\Category\Filament\CategoryModuleSettings`

## Tests

Run: `php vendor/bin/phpunit Modules/Category/Tests`

Test files:

  - `Tests/Filament/CategoryResourceTest.php`
  - `Tests/Unit/CategoryApiControllerTest.php`
  - `Tests/Unit/CategoryManagerTest.php`
  - `Tests/Unit/CategoryTest.php`
  - `Tests/Unit/ContentTestModelForCategories.php`
  - `Tests/Unit/Filament/CategoryResourceTest.php`
  - `Tests/Unit/Filament/ShopCategoryResourceTest.php`

## Service providers

  - `Modules\Category\Providers\CategoryServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
