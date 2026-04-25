# `Faq` module

> **Slug:** `faq`
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

### `faqs` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `question` | `string` | nullable |
  | `answer` | `text` | nullable |
  | `position` | `integer` | nullable, has-default |
  | `is_active` | `integer` | nullable, has-default |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Faq\Models\Faq`

Source: `Models/Faq.php`. Table: `faqs`. 

**Fillable:** `question`, `answer`, `position`, `rel_id`, `rel_type`, `is_active`, `updated_at`, `created_at`

**Casts:**

  - `is_active` → `boolean`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Faq\Filament\FaqModuleSettings` | — | — |
  | `Modules\Faq\Filament\FaqTableList` | — | — |
  | `Modules\Faq\Filament\Resources\FaqModuleResource` | Website Settings | — |
  | `Modules\Faq\Filament\Resources\FaqModuleResource\Pages\CreateFaq` | — | — |
  | `Modules\Faq\Filament\Resources\FaqModuleResource\Pages\EditFaq` | — | — |
  | `Modules\Faq\Filament\Resources\FaqModuleResource\Pages\ListFaqs` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Faq/Tests`

### `Tests/Filament/FaqResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/FaqModuleResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_can_sort_by_position`

## Service providers

  - `Modules\Faq\Providers\FaqServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
