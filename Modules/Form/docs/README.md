# `Form` module

> **Slug:** `form`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
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

Migrations under `Modules/Form/database/migrations/`:

  - `database/migrations/2020_00_00_000001_create_forms_table.php`
  - `database/migrations/2020_00_00_000002_create_forms_data_table.php`
  - `database/migrations/2021_03_17_000000_create_forms_recipients_table.php`
  - `database/migrations/2021_10_21_000000_create_forms_data_values_table.php`
  - `database/migrations/2021_10_22_000000_add_is_read_in_forms_data.php`
  - `database/migrations/2021_10_22_000000_add_updated_at_in_forms_data.php`
  - `database/migrations/2021_10_22_000000_migrate_old_forms_data.php`
  - `database/migrations/2024_10_31_000001_add_module_id_in_forms.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Form\Models\FormData` | `Models/FormData.php` |
| `Modules\Form\Models\FormDataValue` | `Models/FormDataValue.php` |
| `Modules\Form\Models\FormList` | `Models/FormList.php` |
| `Modules\Form\Models\FormRecipient` | `Models/FormRecipient.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Form\Http\Controllers\ApiPublic\FormController`

## Filament admin

  - `Modules\Form\Filament\Resources\FormEntryResource`
  - `Modules\Form\Filament\Resources\FormEntryResource\Pages\ListFormEntries`

## Tests

Run: `php vendor/bin/phpunit Modules/Form/Tests`

Test files:

  - `Tests/ContactFormQuickEmailsTest.php`
  - `Tests/ContactFormSkipSavingEmailsTest.php`
  - `Tests/ContactFormTest.php`
  - `Tests/CustomFieldsTemplatesTest.php`
  - `Tests/CustomFieldsTest.php`
  - `Tests/Filament/FormEntryResourceTest.php`
  - `Tests/FormControllerTest.php`

## Service providers

  - `Modules\Form\Providers\FormServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
