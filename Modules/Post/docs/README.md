# `Post` module

> **Slug:** `post`
> **Tier:** 2
>
> Tier-2 module — service / API surface on top of shared infrastructure.
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

This module owns no migrations of its own.

## Models

| Eloquent class | File |
|---|---|
| `Modules\Post\Models\Post` | `Models/Post.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Post\Http\Controllers\Api\PostApiController`

## Filament admin

  - `Modules\Post\Filament\Admin\Resources\PostResource`
  - `Modules\Post\Filament\Admin\Resources\PostResource\Pages\CreatePost`
  - `Modules\Post\Filament\Admin\Resources\PostResource\Pages\EditPost`
  - `Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts`
  - `Modules\Post\Filament\PostModuleSettings`

## Tests

Run: `php vendor/bin/phpunit Modules/Post/Tests`

Test files:

  - `Tests/Filament/PostResourceTest.php`
  - `Tests/Unit/Filament/PostResourceTest.php`
  - `Tests/Unit/PostApiControllerTest.php`

## Service providers

  - `Modules\Post\Providers\PostServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
