# `Slider` module

> **Slug:** `slider`
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

Migrations under `Modules/Slider/database/migrations/`:

  - `database/migrations/2025_01_15_083825_create_sliders_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Slider\Models\Slider` | `Models/Slider.php` |

## Filament admin

  - `Modules\Slider\Filament\SliderModuleSettings`
  - `Modules\Slider\Filament\SliderTableList`

## Tests

Run: `php vendor/bin/phpunit Modules/Slider/Tests`

Test files:

  - `Tests/Unit/SliderModuleFrontendTest.php`
  - `Tests/Unit/SliderSettingsFilamentTest.php`

## Service providers

  - `Modules\Slider\Providers\SliderServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
