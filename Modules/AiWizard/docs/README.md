# `AiWizard` module

> **Slug:** `ai-wizard`
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

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/api.php`
  - `routes/web.php`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\AiWizard\Filament\Admin\AiWizardResource` | System Settings | AI Page Wizard |
  | `Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\AiWizardPageDesign` | — | — |
  | `Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\CreateAiWizardPage` | — | — |
  | `Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\EditAiWizardPage` | — | — |
  | `Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\ListAiWizardPages` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/AiWizard/Tests`

### `Tests/Unit/Filament/AiWizardResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_create_page_saves_new_record`
  - `it_pages_exist`

## Service providers

  - `Modules\AiWizard\Providers\AiWizardServiceProvider`
  - `Modules\AiWizard\Providers\RouteServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
