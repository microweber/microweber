# `Invoice` module

> **Slug:** `invoice`
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

### `invoices` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `invoice_date` | `date` | nullable |
  | `due_date` | `date` | nullable |
  | `invoice_number` | `string` | nullable |
  | `reference_number` | `string` | nullable |
  | `customer_id` | `integer` | nullable |
  | `company_id` | `integer` | nullable, has-default |
  | `status` | `string` | nullable |
  | `paid_status` | `string` | nullable |
  | `sub_total` | `integer` | nullable |
  | `discount_val` | `integer` | nullable, has-default |
  | `total` | `integer` | nullable |
  | `due_amount` | `integer` | nullable |
  | `tax` | `decimal` | nullable |
  | `notes` | `text` | nullable |
  | `unique_hash` | `string` | nullable |
  | `invoice_template_id` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `invoice_number` | `unique` | — |
  | `discount` | `integer` | nullable |
  | `discount_type` | `string` | nullable |
  | `tax_per_item` | `boolean` | has-default |
  | `discount_per_item` | `boolean` | has-default |
  | `(unnamed)` | `dropColumn` | — |

### `invoice_items` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `invoice_id` | `integer` | nullable |
  | `name` | `string` | nullable |
  | `description` | `text` | nullable |
  | `price` | `decimal` | nullable |
  | `tax` | `decimal` | nullable |
  | `quantity` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Invoice\Models\Invoice`

Source: `Models/Invoice.php`. 

**Fillable:** `invoice_date`, `due_date`, `invoice_number`, `reference_number`, `customer_id`, `company_id`, `invoice_template_id`, `status`, `paid_status`, `sub_total`, `discount`, `discount_type`, `discount_val`, `total`, `due_amount`, `tax_per_item`, `discount_per_item`, `tax`, `notes`, `unique_hash`

**Casts:**

  - `invoice_date` → `date`
  - `due_date` → `date`
  - `tax_per_item` → `boolean`
  - `discount_per_item` → `boolean`
  - `sub_total` → `integer`
  - `discount_val` → `integer`
  - `total` → `integer`
  - `due_amount` → `integer`

### `Modules\Invoice\Models\InvoiceItem`

Source: `Models/InvoiceItem.php`. 

**Fillable:** `invoice_id`, `name`, `description`, `price`, `quantity`

**Casts:**

  - `price` → `integer`
  - `quantity` → `integer`

## API endpoints

### `routes/admin.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/export/invoices` | `InvoiceExportController::export` |

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `InvoicesApiController::index` |
  | `GET` | `/{invoice}` | `InvoicesApiController::show` |
  | `POST` | `/` | `InvoicesApiController::store` |
  | `PUT` | `/{invoice}` | `InvoicesApiController::update` |
  | `PATCH` | `/{invoice}` | `InvoicesApiController::update` |
  | `DELETE` | `/{invoice}` | `InvoicesApiController::destroy` |

## Controllers

### `Modules\Invoice\Http\Controllers\Admin\InvoiceExportController`

Source: `Http/Controllers/Admin/InvoiceExportController.php`.

  - `export(Request $request): StreamedResponse`

### `Modules\Invoice\Http\Controllers\Api\InvoicesApiController`

Source: `Http/Controllers/Api/InvoicesApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Service classes

### `Modules\Invoice\Services\InvoiceService`

Source: `Services/InvoiceService.php`.

  - `generateInvoice(array $params = []): array`
  - `getInvoiceById(int $invoice_id): ?Invoice`
  - `getAllInvoices(): array`
  - `getInvoicesByCustomerId(int $customer_id): array`
  - `saveInvoice(array $data): array`
  - `deleteInvoice(int $invoice_id): array`
  - `updateInvoiceStatus(int $invoice_id, string $status): array`
  - `updateInvoicePaidStatus(int $invoice_id, string $paid_status): array`
  - `generatePdf(Invoice $invoice): string`
  - `sendInvoiceEmail(int $invoice_id, ?string $toEmail = null, ?string $customMessage = null): array`
  - `generateFromOrder(int $order_id, array $params = []): array`
  - `downloadPdf(int $invoice_id)`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Invoice\Filament\Exports\InvoiceExporter` | — | — |
  | `Modules\Invoice\Filament\Pages\AdminShopInvoicesPage` | Shop Settings | — |
  | `Modules\Invoice\Filament\Resources\InvoiceResource` | Shop Settings | Invoices |
  | `Modules\Invoice\Filament\Resources\InvoiceResource\Pages\CreateInvoice` | — | — |
  | `Modules\Invoice\Filament\Resources\InvoiceResource\Pages\EditInvoice` | — | — |
  | `Modules\Invoice\Filament\Resources\InvoiceResource\Pages\ListInvoices` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Invoice/Tests`

### `Tests/Filament/InvoiceResourceTest.php`

  - `it_can_render_invoices_admin_page`

### `Tests/Integration/InvoiceGenerationTest.php`

  - `it_marks_invoice_as_sent`

### `Tests/Unit/Filament/InvoiceResourceTest.php`

  - `it_index_page_shows_records`
  - `it_create_page_validates_required_fields`
  - `it_pdf_export_action_works`
  - `it_has_correct_model`

## Service providers

  - `Modules\Invoice\Providers\InvoiceServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
