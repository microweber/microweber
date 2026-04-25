# `Page` module

> **Slug:** `page`
> **Tier:** 2
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

This module owns no migrations of its own.

## Models

### `Modules\Page\Models\Page`

Source: `Models/Page.php`. Table: `content`. 

**Fillable:** `subtype`, `subtype_value`, `content_type`, `parent`, `layout_file`, `active_site_template`, `title`, `url`, `content_meta_title`, `content`, `description`, `content_body`, `content_meta_keywords`, `original_link`, `require_login`, `created_by`, `is_home`, `is_shop`, `is_active`, `updated_at`, `created_at`, `position`

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `PageApiController::index` |
  | `GET` | `/{page}` | `PageApiController::show` |
  | `POST` | `/` | `PageApiController::store` |
  | `PUT` | `/{page}` | `PageApiController::update` |
  | `PATCH` | `/{page}` | `PageApiController::update` |
  | `DELETE` | `/{page}` | `PageApiController::destroy` |
  | `GET` | `/` | `PageApiController::index` |
  | `GET` | `/{page}` | `PageApiController::show` |
  | `POST` | `/` | `PageApiController::store` |
  | `PUT` | `/{page}` | `PageApiController::update` |
  | `PATCH` | `/{page}` | `PageApiController::update` |
  | `DELETE` | `/{page}` | `PageApiController::destroy` |

## Controllers

### `Modules\Page\Http\Controllers\Api\PageApiController`

Source: `Http/Controllers/Api/PageApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Page\Filament\PageModuleSettings` | — | — |
  | `Modules\Page\Filament\Resources\PageResource` | Website | — |
  | `Modules\Page\Filament\Resources\PageResource\Pages\CreatePage` | — | — |
  | `Modules\Page\Filament\Resources\PageResource\Pages\EditPage` | — | — |
  | `Modules\Page\Filament\Resources\PageResource\Pages\ListPages` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Page/Tests`

### `Tests/Filament/PageResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/PageResourceTest.php`

  - `it_factory_creates_page`
  - `it_has_correct_model`

## Service providers

  - `Modules\Page\Providers\PageServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
