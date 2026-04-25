# `ContentField` module

> **Slug:** `content-field`
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

Migrations under `Modules/ContentField/database/migrations/`:

  - `database/migrations/2022_00_00_000000_create_content_fields_table.php`

*Migrations exist but no `Schema::create/table` blocks could be
auto-extracted; hand-edit to inline the column lists.*

## Models

### `Modules\ContentField\Models\ContentField`

Source: `Models/ContentField.php`. Table: `content_fields`. 

**Fillable:** `rel_type`, `rel_id`, `field`, `value`

## Service providers

  - `Modules\ContentField\Providers\ContentFieldServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
