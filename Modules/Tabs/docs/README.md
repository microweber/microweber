# `Tabs` module

> **Slug:** `tabs`
> **Tier:** 3
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

### `tabs` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `title` | `string` | nullable |
  | `icon` | `string` | nullable |
  | `content` | `longText` | nullable |
  | `position` | `integer` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `settings` | `longText` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Tabs\Models\Tab`

Source: `Models/Tab.php`. 

**Fillable:** `id`, `title`, `icon`, `position`, `rel_id`, `rel_type`, `settings`, `content`

**Casts:**

  - `settings` → `array`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Tabs\Filament\TabsModuleSettings` | — | — |
  | `Modules\Tabs\Filament\TabsTableList` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Tabs/Tests`

## Service providers

  - `Modules\Tabs\Providers\TabsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
