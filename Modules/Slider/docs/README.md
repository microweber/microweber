# `Slider` module

> **Slug:** `slider`
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

### `sliders` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `description` | `text` | nullable |
  | `media` | `string` | nullable |
  | `link` | `string` | nullable |
  | `button_text` | `string` | nullable |
  | `settings` | `longText` | nullable |
  | `rel_id` | `string` | nullable |
  | `rel_type` | `string` | nullable |
  | `position` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Slider\Models\Slider`

Source: `Models/Slider.php`. 

**Fillable:** `name`, `description`, `media`, `link`, `button_text`, `settings`, `rel_id`, `rel_type`, `position`

**Casts:**

  - `settings` → `array`
  - `position` → `integer`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Slider\Filament\SliderModuleSettings` | — | — |
  | `Modules\Slider\Filament\SliderTableList` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Slider/Tests`

## Service providers

  - `Modules\Slider\Providers\SliderServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
