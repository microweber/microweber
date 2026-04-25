# `MailTemplate` module

> **Slug:** `mail-template`
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

Migrations under `Modules/MailTemplate/database/migrations/`:

  - `database/migrations/2024_03_19_000001_create_mail_templates_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\MailTemplate\Models\MailTemplate` | `Models/MailTemplate.php` |

## Service classes

  - `Modules\MailTemplate\Services\MailTemplateService`

## Filament admin

  - `Modules\MailTemplate\Filament\Resources\MailTemplateResource`
  - `Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\CreateMailTemplate`
  - `Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\EditMailTemplate`
  - `Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\ListMailTemplates`

## Tests

Run: `php vendor/bin/phpunit Modules/MailTemplate/Tests`

Test files:

  - `Tests/Filament/MailTemplateResourceTest.php`
  - `Tests/Unit/Filament/MailTemplateResourceTest.php`
  - `Tests/Unit/MailTemplatesTest.php`

## Service providers

  - `Modules\MailTemplate\Providers\MailTemplateServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
