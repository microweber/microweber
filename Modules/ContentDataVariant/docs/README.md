# `ContentDataVariant` module

> **Slug:** `content-data-variant`
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

### `content_data_variants` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `custom_field_id` | `integer` | nullable |
  | `custom_field_value_id` | `integer` | nullable |
  | `rel_id` | `integer` | nullable |
  | `rel_type` | `text` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\ContentDataVariant\Models\ContentDataVariant`

Source: `Models/ContentDataVariant.php`. Table: `content_data_variants`. 

**Fillable:** `rel_type`, `rel_id`, `custom_field_id`, `custom_field_value_id`

## Tests

Run: `php vendor/bin/phpunit Modules/ContentDataVariant/Tests`

## Service providers

  - `Modules\ContentDataVariant\Providers\ContentDataVariantServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
