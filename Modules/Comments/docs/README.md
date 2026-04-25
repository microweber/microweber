# `Comments` module

> **Slug:** `comments`
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

Migrations under `Modules/Comments/database/migrations/`:

  - `database/migrations/2024_01_02_000000_create_comments_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Comments\Models\Comment` | `Models/Comment.php` |
| `Modules\Comments\Models\GatedComment` | `Models/GatedComment.php` |

## API endpoints

Route files:

  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Comments\Http\Controllers\Api\CommentsApiController`

## Service classes

  - `Modules\Comments\Services\AvatarProvider`
  - `Modules\Comments\Services\CommentsManager`

## Filament admin

  - `Modules\Comments\Filament\CommentsModuleSettings`
  - `Modules\Comments\Filament\Pages\CommentsModuleSettingsAdmin`
  - `Modules\Comments\Filament\Resources\CommentResource`
  - `Modules\Comments\Filament\Resources\CommentResource\Pages\CreateComment`
  - `Modules\Comments\Filament\Resources\CommentResource\Pages\EditComment`
  - `Modules\Comments\Filament\Resources\CommentResource\Pages\ListComments`

## Tests

Run: `php vendor/bin/phpunit Modules/Comments/Tests`

Test files:

  - `Tests/Filament/CommentResourceTest.php`
  - `Tests/Unit/CommentModelTest.php`
  - `Tests/Unit/CommentPolicyTest.php`
  - `Tests/Unit/CommentsManagerTest.php`
  - `Tests/Unit/CommentsModuleSettingsTest.php`
  - `Tests/Unit/Filament/CommentResourceTest.php`
  - `Tests/Unit/UserCommentListComponentTest.php`
  - `Tests/Unit/UserCommentReplyComponentTest.php`

## Service providers

  - `Modules\Comments\Providers\CommentsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
