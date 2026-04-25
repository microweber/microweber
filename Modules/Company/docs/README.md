# `Company` module

> **Slug:** `company`
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

### `companies` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `bigIncrements` | — |
  | `name` | `string` | nullable |
  | `description` | `string` | nullable |
  | `company_number` | `string` | nullable |
  | `vat_number` | `string` | nullable |
  | `phone` | `string` | nullable |
  | `email` | `string` | nullable |
  | `address` | `string` | nullable |
  | `city` | `string` | nullable |
  | `zip` | `string` | nullable |
  | `country` | `string` | nullable |
  | `website` | `string` | nullable |
  | `logo` | `string` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `created_by` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Company\Models\Company`

Source: `Models/Company.php`. Table: `companies`. 

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/web.php`

## Service providers

  - `Modules\Company\Providers\CompanyServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
