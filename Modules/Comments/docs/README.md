# `Comments` module

> **Slug:** `comments`
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

### `comments` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `comment_subject` | `string` | nullable |
  | `comment_body` | `longText` | nullable |
  | `comment_name` | `string` | nullable |
  | `comment_email` | `string` | nullable |
  | `comment_website` | `string` | nullable |
  | `user_agent` | `string` | nullable |
  | `reply_to_comment_id` | `integer` | nullable |
  | `created_by` | `integer` | nullable |
  | `session_id` | `string` | nullable |
  | `user_ip` | `string` | nullable |
  | `is_new` | `integer` | nullable, has-default |
  | `is_moderated` | `integer` | nullable, has-default |
  | `is_spam` | `integer` | nullable |
  | `is_reported` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Comments\Models\Comment`

Source: `Models/Comment.php`. Table: `comments`. 

**Fillable:** `comment_subject`, `comment_name`, `comment_email`, `comment_website`, `comment_body`, `rel_type`, `rel_id`, `reply_to_comment_id`, `is_moderated`, `is_new`, `is_spam`, `user_ip`, `session_id`, `created_by`

**Casts:**

  - `rel_type` → `string`
  - `rel_id` → `string`
  - `reply_to_comment_id` → `integer`
  - `is_moderated` → `boolean`
  - `is_new` → `boolean`
  - `is_spam` → `boolean`
  - `is_reported` → `boolean`
  - `created_by` → `integer`
  - `user_ip` → `string`
  - `session_id` → `string`
  - `user_agent` → `string`
  - `comment_body` → `string`
  - `comment_name` → `string`
  - `comment_email` → `string`
  - `comment_website` → `string`
  - `comment_subject` → `string`
  - `created_at` → `datetime`
  - `updated_at` → `datetime`

### `Modules\Comments\Models\GatedComment`

Source: `Models/GatedComment.php`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `CommentsApiController::index` |
  | `GET` | `/{comment}` | `CommentsApiController::show` |
  | `POST` | `/` | `CommentsApiController::store` |
  | `PUT` | `/{comment}` | `CommentsApiController::update` |
  | `PATCH` | `/{comment}` | `CommentsApiController::update` |
  | `DELETE` | `/{comment}` | `CommentsApiController::destroy` |

## Controllers

### `Modules\Comments\Http\Controllers\Api\CommentsApiController`

Source: `Http/Controllers/Api/CommentsApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Service classes

### `Modules\Comments\Services\AvatarProvider`

Source: `Services/AvatarProvider.php`.

  - `getAvatarUrl($comment)`

### `Modules\Comments\Services\CommentsManager`

Source: `Services/CommentsManager.php`.

  - `getConfig($key = null)`
  - `get($params = [])`
  - `create($data)`
  - `update($id, $data)`
  - `delete($id)`
  - `markAsSpam($id)`
  - `approve($id)`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Comments\Filament\CommentsModuleSettings` | — | — |
  | `Modules\Comments\Filament\Pages\CommentsModuleSettingsAdmin` | Settings | Comments |
  | `Modules\Comments\Filament\Resources\CommentResource` | Website Settings | — |
  | `Modules\Comments\Filament\Resources\CommentResource\Pages\CreateComment` | — | — |
  | `Modules\Comments\Filament\Resources\CommentResource\Pages\EditComment` | — | — |
  | `Modules\Comments\Filament\Resources\CommentResource\Pages\ListComments` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Comments/Tests`

### `Tests/Filament/CommentResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/CommentsModuleSettingsTest.php`

  - `it_settingssaving`

### `Tests/Unit/Filament/CommentResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_table_has_required_columns`
  - `it_bulk_mark_as_spam_action_exists`

## Service providers

  - `Modules\Comments\Providers\CommentsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
