# Invoice

Invoice generation and management for e-commerce orders. Create, view, send, and download invoices with line items and discount support.

## Key Features

- Automatic invoice generation from orders
- Line-item detail with quantities and pricing
- Discount columns (percentage and fixed amount)
- PDF invoice generation
- Email delivery of invoices
- Admin panel for invoice browsing and management

## Key Classes

| Class | Purpose |
|---|---|
| `Services\InvoiceService` | Invoice creation and operations (`app('invoice_service')`) |
| `Models\Invoice` | Invoice header (totals, customer, discounts) |
| `Models\InvoiceItem` | Individual line items |
| `Mail\*` | Mailable classes for invoice delivery |

## Database Tables

- `invoices` -- invoice headers with discount columns
- `invoice_items` -- line items per invoice

## Admin Panel (Filament)

- **InvoiceResource** -- browse, view, and manage invoices
- **AdminShopInvoicesPage** -- dedicated shop invoices overview page

## Routes

Admin routes defined in `routes/admin.php`.

## Usage

```php
$invoiceService = app('invoice_service');
$invoice = \Modules\Invoice\Models\Invoice::find(1);
$items = $invoice->items;
```
