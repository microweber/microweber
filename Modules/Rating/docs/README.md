# `Rating` module

> **Slug:** `rating`
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

Migrations under `Modules/Rating/database/migrations/`:

  - `database/migrations/2024_02_20_000001_create_rating_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Rating\Models\Rating` | `Models/Rating.php` |

## API endpoints

Route files:

  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Rating\Http\Controllers\RatingController`

## Filament admin

  - `Modules\Rating\Filament\RatingModuleSettings`
  - `Modules\Rating\Filament\RatingTableList`
  - `Modules\Rating\Filament\Resources\RatingModuleResource`
  - `Modules\Rating\Filament\Resources\RatingModuleResource\Pages\CreateRating`
  - `Modules\Rating\Filament\Resources\RatingModuleResource\Pages\EditRating`
  - `Modules\Rating\Filament\Resources\RatingModuleResource\Pages\ListRatings`

## Tests

Run: `php vendor/bin/phpunit Modules/Rating/Tests`

Test files:

  - `Tests/Filament/RatingResourceTest.php`
  - `Tests/Unit/Filament/RatingModuleResourceTest.php`
  - `Tests/Unit/RatingTest.php`

## Service providers

  - `Modules\Rating\Providers\RatingServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
