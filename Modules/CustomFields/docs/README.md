# `CustomFields` module

> **Slug:** `custom-fields`
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

Migrations under `Modules/CustomFields/database/migrations/`:

  - `database/migrations/2024_11_20_000001_create_custom_fields_table.php`
  - `database/migrations/2024_11_20_000002_create_custom_fields_values_table.php`
  - `database/migrations/2026_03_23_000001_add_indexes_to_custom_fields_values.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\CustomFields\Models\CustomField` | `Models/CustomField.php` |
| `Modules\CustomFields\Models\CustomFieldValue` | `Models/CustomFieldValue.php` |

## Service classes

  - `Modules\CustomFields\Services\FieldsManager`

## Events

  - `Modules\CustomFields\Events\CustomFieldWasDeleted`
  - `Modules\CustomFields\Listeners\AddCustomFieldProductListener`
  - `Modules\CustomFields\Listeners\EditCustomFieldProductListener`
  - `Modules\CustomFields\Listeners\ModifyCustomFieldProductTrait`

## Filament admin

  - `Modules\CustomFields\Filament\Admin\ListCustomFields`
  - `Modules\CustomFields\Filament\CustomFieldsModuleSettings`

## Tests

Run: `php vendor/bin/phpunit Modules/CustomFields/Tests`

Test files:

  - `Tests/Unit/CustomFieldModelTest.php`
  - `Tests/Unit/CustomFieldRenderTest.php`

## Service providers

  - `Modules\CustomFields\Providers\CustomFieldsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
