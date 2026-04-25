# `Attributes` module

> **Slug:** `attributes`
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

### `attributes` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `attribute_name` | `text` | nullable |
  | `attribute_value` | `longText` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `attribute_type` | `string` | nullable |
  | `session_id` | `string` | nullable |
  | `updated_at` | `dateTime` | nullable |
  | `created_at` | `dateTime` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |

## Models

### `Modules\Attributes\Models\Attribute`

Source: `Models/Attribute.php`. Table: `attributes`. 

**Fillable:** `attribute_name`, `attribute_value`, `rel_type`, `rel_id`, `attribute_type`, `session_id`, `updated_at`, `created_at`, `created_by`, `edited_by`

## Tests

Run: `php vendor/bin/phpunit Modules/Attributes/Tests`

### `Tests/Unit/AttributesTest.php`

  - `it_get_attribute`

## Service providers

  - `Modules\Attributes\Providers\AttributesServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
