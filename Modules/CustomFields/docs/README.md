# `CustomFields` module

> **Slug:** `custom-fields`
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

### `custom_fields` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `position` | `integer` | nullable |
  | `type` | `string` | nullable |
  | `name` | `text` | nullable |
  | `name_key` | `text` | nullable |
  | `placeholder` | `text` | nullable |
  | `error_text` | `text` | nullable |
  | `updated_at` | `dateTime` | nullable |
  | `created_at` | `dateTime` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |
  | `session_id` | `string` | nullable |
  | `options` | `longText` | nullable |
  | `show_label` | `integer` | nullable |
  | `is_active` | `integer` | nullable |
  | `required` | `integer` | nullable |
  | `copy_of_field` | `integer` | nullable |

### `custom_fields_values` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `custom_field_id` | `integer` | nullable |
  | `value` | `text` | nullable |
  | `price_modifier` | `integer` | nullable |
  | `position` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `custom_field_id` | `index` | — |
  | `(unnamed)` | `dropIndex` | — |

## Models

### `Modules\CustomFields\Models\CustomField`

Source: `Models/CustomField.php`. Table: `custom_fields`. 

**Fillable:** `rel_id`, `rel_type`, `type`, `options`, `name`, `name_key`, `value`, `session_id`, `position`, `created_by`

### `Modules\CustomFields\Models\CustomFieldValue`

Source: `Models/CustomFieldValue.php`. Table: `custom_fields_values`. 

**Fillable:** `custom_field_id`, `value`, `position`

## Service classes

### `Modules\CustomFields\Services\FieldsManager`

Source: `Services/FieldsManager.php`.

  - `getById($field_id)`
  - `parseFieldSettings($fieldParse)`
  - `parseFieldsHtml($fieldParseInput)`
  - `makeDefault($rel, $rel_id, $fields_csv_str)`
  - `save($fieldData)`
  - `generateFieldNameValues($fieldData)`
  - `getFieldNameByType($type)`
  - `getValues($custom_field_id)`
  - `getValue($content_id, $field_name, $return_full = false, $table = 'content')`
  - `getAll($params)`
  - `get($params)`
  - `decodeArrayVals($it)`
  - `reorder($data)`
  - `delete($id)`
  - `makeField($field_id = 0)`
  - `make($params, $field_type = 'text', $settings = false)`
  - `instanceField($type)`

## Events

  - `Modules\CustomFields\Events\CustomFieldWasDeleted`
  - `Modules\CustomFields\Listeners\AddCustomFieldProductListener`
  - `Modules\CustomFields\Listeners\EditCustomFieldProductListener`
  - `Modules\CustomFields\Listeners\ModifyCustomFieldProductTrait`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\CustomFields\Filament\Admin\ListCustomFields` | — | — |
  | `Modules\CustomFields\Filament\CustomFieldsModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/CustomFields/Tests`

### `Tests/Unit/CustomFieldModelTest.php`

  - `it_custom_field_model_values_attribute`

### `Tests/Unit/CustomFieldRenderTest.php`

  - `it_rendering_text_area_field`
  - `it_rendering_number_field`
  - `it_rendering_color_field`

## Service providers

  - `Modules\CustomFields\Providers\CustomFieldsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
