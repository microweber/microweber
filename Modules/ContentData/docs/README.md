# `ContentData` module

> **Slug:** `content-data`
> **Tier:** 4
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

### `content_data` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `field_name` | `text` | nullable |
  | `field_value` | `longText` | nullable |
  | `updated_at` | `dateTime` | nullable |
  | `created_at` | `dateTime` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |
  | `rel_type` | `index` | — |
  | `rel_id` | `index` | — |
  | `field_name` | `index` | — |
  | `field_value` | `fullText` | — |
  | `(unnamed)` | `dropIndex` | — |
  | `(unnamed)` | `dropIndex` | — |
  | `(unnamed)` | `dropIndex` | — |
  | `(unnamed)` | `dropFullText` | — |
  | `rel_type` | `index` | — |
  | `rel_id` | `index` | — |
  | `field_name` | `index` | — |
  | `(unnamed)` | `dropIndex` | — |

## Models

### `Modules\ContentData\Models\ContentData`

Source: `Models/ContentData.php`. Table: `content_data`. 

**Fillable:** `id`, `rel_type`, `rel_id`, `field_value`, `field_name`, `content_id`, `created_at`, `updated_at`, `edited_by`, `created_by`

## Tests

Run: `php vendor/bin/phpunit Modules/ContentData/Tests`

## Service providers

  - `Modules\ContentData\Providers\ContentDataServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
