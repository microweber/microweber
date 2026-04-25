# `Tax` module

> **Slug:** `tax`
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

Migrations under `Modules/Tax/database/migrations/`:

  - `database/migrations/2024_07_23_000002_create_tax_types_table.php`
  - `database/migrations/2026_03_21_000001_create_tax_rates_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Tax\Models\TaxRate` | `Models/TaxRate.php` |
| `Modules\Tax\Models\TaxType` | `Models/TaxType.php` |

## API endpoints

Route files:

  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Tax\Http\Controllers\Api\TaxApiController`

## Service classes

  - `Modules\Tax\Services\TaxCalculator`
  - `Modules\Tax\Services\TaxManager`

## Filament admin

  - `Modules\Tax\Filament\Admin\Resources\TaxRateResource`
  - `Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\CreateTaxRate`
  - `Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\EditTaxRate`
  - `Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\ListTaxRates`
  - `Modules\Tax\Filament\Admin\Resources\TaxResource`
  - `Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\CreateTax`
  - `Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\EditTax`
  - `Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\ListTaxes`

## Tests

Run: `php vendor/bin/phpunit Modules/Tax/Tests`

Test files:

  - `Tests/Filament/TaxResourceTest.php`
  - `Tests/TaxCalculatorTest.php`
  - `Tests/TaxCartTest.php`
  - `Tests/TaxRateModelTest.php`
  - `Tests/TaxResourceTest.php`

## Service providers

  - `Modules\Tax\Providers\TaxServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
