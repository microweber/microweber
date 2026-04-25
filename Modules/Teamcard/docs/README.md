# `Teamcard` module

> **Slug:** `teamcard`
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

### `teamcards` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `file` | `string` | nullable |
  | `bio` | `longText` | nullable |
  | `role` | `string` | nullable |
  | `website` | `string` | nullable |
  | `position` | `integer` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `settings` | `longText` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Teamcard\Models\Teamcard`

Source: `Models/Teamcard.php`. 

**Fillable:** `name`, `file`, `bio`, `role`, `website`, `position`, `rel_type`, `rel_id`, `settings`

**Casts:**

  - `settings` → `array`

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/api.php`
  - `routes/web.php`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Teamcard\Filament\TeamcardModuleSettings` | — | — |
  | `Modules\Teamcard\Filament\TeamcardTableList` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Teamcard/Tests`

## Service providers

  - `Modules\Teamcard\Providers\TeamcardServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
