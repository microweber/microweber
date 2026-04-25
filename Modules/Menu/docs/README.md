# `Menu` module

> **Slug:** `menu`
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

### `menus` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `title` | `text` | nullable |
  | `item_type` | `text` | nullable |
  | `description` | `text` | nullable |
  | `url` | `longText` | nullable |
  | `url_target` | `text` | nullable |
  | `parent_id` | `integer` | nullable |
  | `content_id` | `integer` | nullable |
  | `categories_id` | `integer` | nullable |
  | `position` | `integer` | nullable |
  | `is_active` | `integer` | nullable |
  | `auto_populate` | `integer` | nullable |
  | `size` | `text` | nullable |
  | `default_image` | `text` | nullable |
  | `rollover_image` | `text` | nullable |
  | `timestamps` | `timestamps` | — |
  | `(unnamed)` | `dropColumn` | — |

## Models

### `Modules\Menu\Models\Menu`

Source: `Models/Menu.php`. 

**Casts:**

  - `mega_menu_settings` → `array`

### `Modules\Menu\Models\MenuItem`

Source: `Models/MenuItem.php`. Table: `menus`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `MenusApiController::index` |
  | `GET` | `/{menu}` | `MenusApiController::show` |
  | `POST` | `/` | `MenusApiController::store` |
  | `PUT` | `/{menu}` | `MenusApiController::update` |
  | `PATCH` | `/{menu}` | `MenusApiController::update` |
  | `DELETE` | `/{menu}` | `MenusApiController::destroy` |

## Controllers

### `Modules\Menu\Http\Controllers\Api\MenusApiController`

Source: `Http/Controllers/Api/MenusApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Menu\Filament\Admin\Pages\AdminMenusPage` | Website Settings | — |
  | `Modules\Menu\Filament\MenuModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Menu/Tests`

### `Tests/Filament/MenuPageTest.php`

  - `it_menus_page_class_exists`

### `Tests/Unit/MenuContentModelTest.php`

  - `it_if_menu_ids_attrbute_is_saved_from_set_menu_ids_method`

## Service providers

  - `Modules\Menu\Providers\MenuServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
