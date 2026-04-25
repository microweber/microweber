# `GoogleMaps` module

> **Slug:** `google-maps`
> **Tier:** 3
>
> Tier-3 module — admin tool / widget driven by a Filament page or resource.
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

## Filament admin

  - `Modules\GoogleMaps\Filament\GoogleMapsModuleSettings`

## Tests

Run: `php vendor/bin/phpunit Modules/GoogleMaps/Tests`

Test files:

  - `Tests/Unit/GoogleMapsModuleFrontendTest.php`
  - `Tests/Unit/GoogleMapsModuleSettingsTest.php`

## Service providers

  - `Modules\GoogleMaps\Providers\GoogleMapsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
