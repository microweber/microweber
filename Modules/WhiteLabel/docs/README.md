# `WhiteLabel` module

> **Slug:** `white-label`
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

This module owns no migrations of its own.

## Service classes

### `Modules\WhiteLabel\Services\WhiteLabelService`

Source: `Services/WhiteLabelService.php`.

  - `applyWhiteLabelSettings()`
  - `saveWhiteLabelConfig($params)`
  - `getWhiteLabelConfig()`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\WhiteLabel\Filament\Admin\WhiteLabelLicenseManager` | — | — |
  | `Modules\WhiteLabel\Filament\Pages\WhiteLabelSettingsAdminSettingsPage` | Customization Settings | — |

## Service providers

  - `Modules\WhiteLabel\Providers\WhiteLabelServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
