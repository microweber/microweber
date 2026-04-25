# `ContentData` module

> **Slug:** `content-data`
> **Tier:** 4
>
> Tier-4 module — pure presentation / template-side widget.
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

Migrations under `Modules/ContentData/database/migrations/`:

  - `database/migrations/2020_00_00_000000_create_content_data_table.php`
  - `database/migrations/2022_10_04_000001_add_index_content_data_table.php`
  - `database/migrations/2026_03_23_000001_add_indexes_to_content_data.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\ContentData\Models\ContentData` | `Models/ContentData.php` |

## Tests

Run: `php vendor/bin/phpunit Modules/ContentData/Tests`

Test files:

  - `Tests/Unit/ContentDataTest.php`
  - `Tests/Unit/CustomModelContentDataTest.php`
  - `Tests/Unit/TestModel.php`

## Service providers

  - `Modules\ContentData\Providers\ContentDataServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
