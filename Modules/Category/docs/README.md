# `Category` module

> **Slug:** `category`
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

### `categories` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `updated_at` | `dateTime` | nullable |
  | `created_at` | `dateTime` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |
  | `data_type` | `string` | nullable |
  | `title` | `text` | nullable |
  | `url` | `longText` | nullable |
  | `parent_id` | `integer` | nullable |
  | `description` | `text` | nullable |
  | `content` | `longText` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `integer` | nullable |
  | `position` | `integer` | nullable |
  | `is_deleted` | `integer` | nullable, has-default |
  | `is_hidden` | `integer` | nullable, has-default |
  | `is_active` | `integer` | nullable, has-default |
  | `users_can_create_subcategories` | `integer` | nullable |
  | `users_can_create_content` | `integer` | nullable |
  | `users_can_create_content_allowed_usergroups` | `string` | nullable |
  | `category_meta_title` | `text` | nullable |
  | `category_meta_keywords` | `text` | nullable |
  | `category_meta_description` | `text` | nullable |
  | `category_subtype` | `string` | nullable |
  | `category_subtype_settings` | `longText` | nullable |
  | `parent_id` | `index` | — |
  | `is_active` | `index` | — |
  | `(unnamed)` | `dropIndex` | — |

### `categories_items` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `parent_id` | `integer` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Category\Models\Category`

Source: `Models/Category.php`. Table: `categories`. 

### `Modules\Category\Models\CategoryItem`

Source: `Models/CategoryItem.php`. 

### `Modules\Category\Models\ModelFilters\CategoryFilter`

Source: `Models/ModelFilters/CategoryFilter.php`. 

### `Modules\Category\Models\ModelFilters\Traits\FilterByAvailableProductsByCategoryTrait`

Source: `Models/ModelFilters/Traits/FilterByAvailableProductsByCategoryTrait.php`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `CategoriesApiController::index` |
  | `GET` | `/{category}` | `CategoriesApiController::show` |
  | `POST` | `/` | `CategoriesApiController::store` |
  | `PUT` | `/{category}` | `CategoriesApiController::update` |
  | `PATCH` | `/{category}` | `CategoriesApiController::update` |
  | `DELETE` | `/{category}` | `CategoriesApiController::destroy` |

## Controllers

### `Modules\Category\Http\Controllers\Api\CategoriesApiController`

Source: `Http/Controllers/Api/CategoriesApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

### `Modules\Category\Http\Controllers\Api\CategoryApiController`

Source: `Http/Controllers/Api/CategoryApiController.php`.

  - `index(CategoryRequest $request)`
  - `store(CategoryRequest $request)`
  - `show($id)`
  - `update(CategoryRequest $request, $id)`
  - `delete(CategoryRequest $request, $id)`
  - `destroy(CategoryRequest $request)`
  - `hiddenBulk(CategoryRequest $request)`
  - `visibleBulk(CategoryRequest $request)`
  - `moveBulk(CategoryRequest $request)`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Category\Filament\Admin\Resources\CategoryResource` | Website | — |
  | `Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory` | — | — |
  | `Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\EditCategory` | — | — |
  | `Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories` | — | — |
  | `Modules\Category\Filament\Admin\Resources\ShopCategoryResource` | Shop | — |
  | `Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\CreateShopCategory` | — | — |
  | `Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\EditShopCategory` | — | — |
  | `Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories` | — | — |
  | `Modules\Category\Filament\CategoryModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Category/Tests`

### `Tests/Filament/CategoryResourceTest.php`

  - `it_can_render_shop_categories_list_page`
  - `it_can_create_and_delete_category`

### `Tests/Unit/CategoryTest.php`

  - `it_categories_same_slug`

### `Tests/Unit/Filament/CategoryResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_global_search_returns_results`

### `Tests/Unit/Filament/ShopCategoryResourceTest.php`

  - `it_pages_exist`

## Service providers

  - `Modules\Category\Providers\CategoryServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
