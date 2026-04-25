# `MailTemplate` module

> **Slug:** `mail-template`
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

### `mail_templates` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `type` | `string` | nullable |
  | `from_name` | `string` | nullable |
  | `from_email` | `string` | nullable |
  | `copy_to` | `string` | nullable |
  | `subject` | `string` | nullable |
  | `message` | `text` | nullable |
  | `is_active` | `boolean` | has-default |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\MailTemplate\Models\MailTemplate`

Source: `Models/MailTemplate.php`. 

**Fillable:** `name`, `type`, `from_name`, `from_email`, `copy_to`, `subject`, `message`, `is_active`

**Casts:**

  - `is_active` → `boolean`

## Service classes

### `Modules\MailTemplate\Services\MailTemplateService`

Source: `Services/MailTemplateService.php`.

  - `registerMailTemplatePath(string $path): bool`
  - `getMailTemplateFiles(): array`
  - `getTemplateContent(string $name): ?string`
  - `getTemplateByType(string $type): ?MailTemplate`
  - `getTemplateById($id): ?MailTemplate`
  - `parseTemplate(MailTemplate $template, array $variables = []): string`
  - `createMailable(MailTemplate $template, array $variables = [], array $attachments = []): TemplateBasedMail`
  - `send(MailTemplate $template, string $to, array $variables = [], array $attachments = []): void`
  - `getAvailableVariables(string $type): array`
  - `getTemplateTypes(): array`
  - `getDefaultFromName(): string`
  - `getDefaultFromEmail(): string`
  - `getTemplateFormSchema(): array`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\MailTemplate\Filament\Resources\MailTemplateResource` | Email Settings | — |
  | `Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\CreateMailTemplate` | — | — |
  | `Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\EditMailTemplate` | — | — |
  | `Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\ListMailTemplates` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/MailTemplate/Tests`

### `Tests/Filament/MailTemplateResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/MailTemplateResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_sorting_by_column_changes_order`

### `Tests/Unit/MailTemplatesTest.php`

  - `it_default_email_settings`

## Service providers

  - `Modules\MailTemplate\Providers\MailTemplateServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
