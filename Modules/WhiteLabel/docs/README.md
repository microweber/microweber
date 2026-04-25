# `WhiteLabel` module

> **Slug:** `white-label`
> **Tier:** 2
>
> Tier-2 module — service / API surface on top of shared infrastructure.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

This module owns no migrations of its own.

## Service classes

  - `Modules\WhiteLabel\Services\WhiteLabelService`

## Filament admin

  - `Modules\WhiteLabel\Filament\Admin\WhiteLabelLicenseManager`
  - `Modules\WhiteLabel\Filament\Pages\WhiteLabelSettingsAdminSettingsPage`

## Service providers

  - `Modules\WhiteLabel\Providers\WhiteLabelServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
