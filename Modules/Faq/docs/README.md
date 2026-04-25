# `Faq` module

> **Slug:** `faq`
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

Migrations under `Modules/Faq/database/migrations/`:

  - `database/migrations/2024_02_06_000002_create_faqs_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Faq\Models\Faq` | `Models/Faq.php` |

## Filament admin

  - `Modules\Faq\Filament\FaqModuleSettings`
  - `Modules\Faq\Filament\FaqTableList`
  - `Modules\Faq\Filament\Resources\FaqModuleResource`
  - `Modules\Faq\Filament\Resources\FaqModuleResource\Pages\CreateFaq`
  - `Modules\Faq\Filament\Resources\FaqModuleResource\Pages\EditFaq`
  - `Modules\Faq\Filament\Resources\FaqModuleResource\Pages\ListFaqs`

## Tests

Run: `php vendor/bin/phpunit Modules/Faq/Tests`

Test files:

  - `Tests/Filament/FaqResourceTest.php`
  - `Tests/Unit/FaqModuleFrontendTest.php`
  - `Tests/Unit/FaqSettingsFilamentTest.php`
  - `Tests/Unit/Filament/FaqModuleResourceTest.php`

## Service providers

  - `Modules\Faq\Providers\FaqServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
