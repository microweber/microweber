# `Address` module

> **Slug:** `address`
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

### `addresses` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `name` | `string` | nullable |
  | `address_street_1` | `string` | nullable |
  | `address_street_2` | `string` | nullable |
  | `city` | `string` | nullable |
  | `state` | `string` | nullable |
  | `country` | `string` | nullable |
  | `country_id` | `integer` | nullable |
  | `zip` | `string` | nullable |
  | `phone` | `string` | nullable |
  | `type` | `string` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `created_by` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Address\Models\Address`

Source: `Models/Address.php`. 

**Fillable:** `name`, `address_street_1`, `address_street_2`, `city`, `state`, `country`, `country_id`, `zip`, `phone`, `type`, `customer_id`, `rel_type`, `rel_id`

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/web.php`

## Tests

Run: `php vendor/bin/phpunit Modules/Address/Tests`

## Service providers

  - `Modules\Address\Providers\AddressServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
