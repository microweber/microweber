# `Post` module

> **Slug:** `post`
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

### `Modules\Post\Models\Post`

Source: `Models/Post.php`. Table: `content`. 

**Fillable:** `subtype`, `subtype_value`, `content_type`, `parent`, `layout_file`, `active_site_template`, `title`, `url`, `content_meta_title`, `content`, `description`, `content_body`, `content_meta_keywords`, `original_link`, `require_login`, `created_by`, `is_home`, `is_shop`, `is_active`, `is_deleted`, `updated_at`, `created_at`

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `PostApiController::index` |
  | `GET` | `/{post}` | `PostApiController::show` |
  | `POST` | `/` | `PostApiController::store` |
  | `PUT` | `/{post}` | `PostApiController::update` |
  | `PATCH` | `/{post}` | `PostApiController::update` |
  | `DELETE` | `/{post}` | `PostApiController::destroy` |
  | `GET` | `/` | `PostApiController::index` |
  | `GET` | `/{post}` | `PostApiController::show` |
  | `POST` | `/` | `PostApiController::store` |
  | `PUT` | `/{post}` | `PostApiController::update` |
  | `PATCH` | `/{post}` | `PostApiController::update` |
  | `DELETE` | `/{post}` | `PostApiController::destroy` |

## Controllers

### `Modules\Post\Http\Controllers\Api\PostApiController`

Source: `Http/Controllers/Api/PostApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Post\Filament\Admin\Resources\PostResource` | Website | — |
  | `Modules\Post\Filament\Admin\Resources\PostResource\Pages\CreatePost` | — | — |
  | `Modules\Post\Filament\Admin\Resources\PostResource\Pages\EditPost` | — | — |
  | `Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts` | — | — |
  | `Modules\Post\Filament\PostModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Post/Tests`

### `Tests/Filament/PostResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/PostResourceTest.php`

  - `it_factory_creates_post`
  - `it_has_correct_model`

## Service providers

  - `Modules\Post\Providers\PostServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
