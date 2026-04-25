# `Tag` module

> **Slug:** `tag`
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

Migrations under `Modules/Tag/database/migrations/`:

  - `database/migrations/2014_01_07_073615_create_tagged_table.php`
  - `database/migrations/2024_03_20_000001_create_tag_groups_table.php`
  - `database/migrations/2024_03_20_000002_create_tags_table.php`
  - `database/migrations/2024_03_20_000003_create_tagged_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Tag\Models\Tag` | `Models/Tag.php` |
| `Modules\Tag\Models\TagGroup` | `Models/TagGroup.php` |
| `Modules\Tag\Models\Tagged` | `Models/Tagged.php` |
| `Modules\Tag\Models\TranslateTaggingTagged` | `Models/TranslateTaggingTagged.php` |
| `Modules\Tag\Models\TranslateTaggingTags` | `Models/TranslateTaggingTags.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Tag\Http\Controllers\Api\TagApiController`

## Filament admin

  - `Modules\Tag\Filament\Resources\TagGroupResource`
  - `Modules\Tag\Filament\Resources\TagGroupResource\Pages\CreateTagGroup`
  - `Modules\Tag\Filament\Resources\TagGroupResource\Pages\EditTagGroup`
  - `Modules\Tag\Filament\Resources\TagGroupResource\Pages\ListTagGroups`
  - `Modules\Tag\Filament\Resources\TagResource`
  - `Modules\Tag\Filament\Resources\TagResource\Pages\CreateTag`
  - `Modules\Tag\Filament\Resources\TagResource\Pages\EditTag`
  - `Modules\Tag\Filament\Resources\TagResource\Pages\ListTags`
  - `Modules\Tag\Filament\Resources\TaggedResource`
  - `Modules\Tag\Filament\Resources\TaggedResource\Pages\CreateTagged`
  - `Modules\Tag\Filament\Resources\TaggedResource\Pages\EditTagged`
  - `Modules\Tag\Filament\Resources\TaggedResource\Pages\ListTagged`
  - `Modules\Tag\Filament\TagsModuleSettings`

## Tests

Run: `php vendor/bin/phpunit Modules/Tag/Tests`

Test files:

  - `Tests/Filament/TagResourceTest.php`
  - `Tests/TagsTest.php`
  - `Tests/Unit/Filament/TagGroupResourceTest.php`
  - `Tests/Unit/Filament/TagResourceTest.php`
  - `Tests/Unit/Filament/TaggedResourceTest.php`

## Service providers

  - `Modules\Tag\Providers\TagServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
