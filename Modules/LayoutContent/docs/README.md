# `LayoutContent` module

> **Slug:** `layout-content`
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

### `layout_content_items` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `title` | `string` | nullable |
  | `description` | `text` | nullable |
  | `image` | `string` | nullable |
  | `image_alt_text` | `string` | nullable |
  | `button_text` | `string` | nullable |
  | `button_link` | `string` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `position` | `integer` | has-default |
  | `position` | `dropColumn` | — |

## Models

### `Modules\LayoutContent\Models\LayoutContentItem`

Source: `Models/LayoutContentItem.php`. 

**Fillable:** `title`, `description`, `image`, `image_alt_text`, `button_text`, `button_link`, `rel_type`, `rel_id`

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/api.php`
  - `routes/web.php`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\LayoutContent\Filament\LayoutContentModuleSettings` | — | — |
  | `Modules\LayoutContent\Filament\LayoutContentTableList` | — | — |

## Service providers

  - `Modules\LayoutContent\Providers\EventServiceProvider`
  - `Modules\LayoutContent\Providers\LayoutContentServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
