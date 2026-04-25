# `Accordion` module

> **Slug:** `accordion`
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

Migrations under `Modules/Accordion/database/migrations/`:

  - `database/migrations/2024_11_08_000001_create_accordion_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Accordion\Models\Accordion` | `Models/Accordion.php` |

## Filament admin

  - `Modules\Accordion\Filament\AccordionModuleSettings`
  - `Modules\Accordion\Filament\AccordionTableList`

## Tests

Run: `php vendor/bin/phpunit Modules/Accordion/Tests`

Test files:

  - `Tests/Unit/AccordionModuleFrontendTest.php`
  - `Tests/Unit/AccordionTableListFilamentTest.php`

## Service providers

  - `Modules\Accordion\Providers\AccordionServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
