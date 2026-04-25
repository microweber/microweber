# `Log` module

> **Slug:** `log`
> **Tier:** 2
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

### `logs` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `level` | `string` | nullable |
  | `message` | `text` | nullable |
  | `rel_type` | `text` | nullable |
  | `rel_id` | `text` | nullable |
  | `channel` | `string` | nullable, has-default |
  | `logged_at` | `timestamp` | nullable |
  | `is_system` | `text` | nullable |
  | `field` | `text` | nullable |
  | `rel` | `text` | nullable |
  | `value` | `text` | nullable |
  | `timestamps` | `timestamps` | — |

## Service classes

### `Modules\Log\Services\LogManager`

Source: `Services/LogManager.php`.

  - `get_entry_by_id($id)`
  - `get($params)`
  - `reset()`
  - `delete($params)`
  - `save($params)`
  - `delete_entry($data)`

## Tests

Run: `php vendor/bin/phpunit Modules/Log/Tests`

## Service providers

  - `Modules\Log\Providers\LogServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
