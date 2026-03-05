<?php

namespace Modules\Invoice\Filament\Exports;

use Modules\Invoice\Models\Invoice;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class InvoiceExporter extends Exporter
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('invoice_number')
                ->label('Invoice Number'),
            ExportColumn::make('customer.name')
                ->label('Customer Name'),
            ExportColumn::make('customer.email')
                ->label('Customer Email'),
            ExportColumn::make('invoice_date')
                ->label('Invoice Date'),
            ExportColumn::make('due_date')
                ->label('Due Date'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('paid_status')
                ->label('Payment Status'),
            ExportColumn::make('subtotal')
                ->label('Subtotal'),
            ExportColumn::make('tax_amount')
                ->label('Tax Amount'),
            ExportColumn::make('total')
                ->label('Total'),
            ExportColumn::make('paid_amount')
                ->label('Paid Amount'),
            ExportColumn::make('reference_number')
                ->label('Reference Number'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('updated_at')
                ->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your invoice export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return 'invoices-' . date('Y-m-d-H-i-s');
    }
}
