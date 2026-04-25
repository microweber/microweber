# `Tabs` module

> **Slug:** `tabs`
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

Migrations under `Modules/Tabs/database/migrations/`:

  - `database/migrations/2024_10_29_093609_create_tabs_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Tabs\Models\Tab` | `Models/Tab.php` |

## Filament admin

  - `Modules\Tabs\Filament\TabsModuleSettings`
  - `Modules\Tabs\Filament\TabsTableList`

## Tests

Run: `php vendor/bin/phpunit Modules/Tabs/Tests`

Test files:

  - `Tests/Unit/TabsModuleFrontendTest.php`
  - `Tests/Unit/TabsModuleSettingsTest.php`
  - `Tests/Unit/TabsModuleTest.php`
  - `Tests/Unit/TabsTableListFilamentTest.php`

## Service providers

  - `Modules\Tabs\Providers\TabsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
