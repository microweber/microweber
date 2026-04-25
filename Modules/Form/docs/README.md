# `Form` module

> **Slug:** `form`
> **Tier:** 1
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

### `forms` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `slug` | `string` | nullable |
  | `list_id` | `integer` | nullable |
  | `module_id` | `integer` | nullable |
  | `description` | `longText` | nullable |
  | `confirmation_message` | `longText` | nullable |
  | `emails_notifications` | `longText` | nullable |
  | `emails_notifications_subject` | `longText` | nullable |
  | `is_active` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `module_id` | `string` | nullable |
  | `module_id` | `dropColumn` | — |

### `forms_recipients` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `bigIncrements` | — |
  | `name` | `string` | nullable |
  | `email` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

### `forms_data_values` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `bigIncrements` | — |
  | `form_data_id` | `integer` | nullable |
  | `field_type` | `string` | nullable |
  | `field_key` | `string` | nullable |
  | `field_name` | `string` | nullable |
  | `field_value` | `longText` | nullable |
  | `field_value_json` | `longText` | nullable |

### `forms_data` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `is_read` | `integer` | nullable |
  | `is_read` | `dropColumn` | — |
  | `updated_at` | `timestamp` | nullable |
  | `updated_at` | `dropColumn` | — |

## Models

### `Modules\Form\Models\FormData`

Source: `Models/FormData.php`. Table: `forms_data`. 

### `Modules\Form\Models\FormDataValue`

Source: `Models/FormDataValue.php`. Table: `forms_data_values`. 

### `Modules\Form\Models\FormList`

Source: `Models/FormList.php`. Table: `forms_lists`. 

### `Modules\Form\Models\FormRecipient`

Source: `Models/FormRecipient.php`. 

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/api.php`

## Controllers

### `Modules\Form\Http\Controllers\ApiPublic\FormController`

Source: `Http/Controllers/ApiPublic/FormController.php`.

  - `post(Request $request)`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Form\Filament\Resources\FormEntryResource` | — | — |
  | `Modules\Form\Filament\Resources\FormEntryResource\Pages\ListFormEntries` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Form/Tests`

### `Tests/Filament/FormEntryResourceTest.php`

  - `it_resource_has_correct_model`

## Service providers

  - `Modules\Form\Providers\FormServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
