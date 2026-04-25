# `ImageRollover` module

> **Slug:** `image-rollover`
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

This module owns no migrations of its own.

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\ImageRollover\Filament\ImageRolloverModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/ImageRollover/Tests`

### `Tests/Unit/ImageRolloverFrontendTest.php`

  - `it_module_accepts_parameters`

### `Tests/Unit/ImageRolloverModuleSettingsFilamentTest.php`

  - `it_settings_has_correct_module_identifier`

### `Tests/Unit/ImageRolloverModuleTest.php`

  - `it_module_initialization`

## Service providers

  - `Modules\ImageRollover\Providers\ImageRolloverServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
