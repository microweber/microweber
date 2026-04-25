# `Invoice` module

> **Slug:** `invoice`
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

Migrations under `Modules/Invoice/database/migrations/`:

  - `database/migrations/2024_12_13_000001_create_invoices_table.php`
  - `database/migrations/2024_12_13_000002_create_invoice_items_table.php`
  - `database/migrations/2025_03_28_000000_add_discount_columns_to_invoices_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Invoice\Models\Invoice` | `Models/Invoice.php` |
| `Modules\Invoice\Models\InvoiceItem` | `Models/InvoiceItem.php` |

## API endpoints

Route files:

  - `routes/admin.php`
  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Invoice\Http\Controllers\Admin\InvoiceExportController`
  - `Modules\Invoice\Http\Controllers\Api\InvoicesApiController`

## Service classes

  - `Modules\Invoice\Services\InvoiceService`

## Filament admin

  - `Modules\Invoice\Filament\Exports\InvoiceExporter`
  - `Modules\Invoice\Filament\Pages\AdminShopInvoicesPage`
  - `Modules\Invoice\Filament\Resources\InvoiceResource`
  - `Modules\Invoice\Filament\Resources\InvoiceResource\Pages\CreateInvoice`
  - `Modules\Invoice\Filament\Resources\InvoiceResource\Pages\EditInvoice`
  - `Modules\Invoice\Filament\Resources\InvoiceResource\Pages\ListInvoices`

## Tests

Run: `php vendor/bin/phpunit Modules/Invoice/Tests`

Test files:

  - `Tests/Filament/InvoiceResourceTest.php`
  - `Tests/Integration/InvoiceGenerationTest.php`
  - `Tests/Integration/InvoiceModelTest.php`
  - `Tests/Integration/InvoiceServiceTest.php`
  - `Tests/Unit/Filament/InvoiceResourceTest.php`

## Service providers

  - `Modules\Invoice\Providers\InvoiceServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
