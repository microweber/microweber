<?php

namespace Modules\Order\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('order_reference_id')
                ->label('Order Reference'),
            ExportColumn::make('email')
                ->label('Email'),
            ExportColumn::make('first_name')
                ->label('First Name'),
            ExportColumn::make('last_name')
                ->label('Last Name'),
            ExportColumn::make('customer_id')
                ->label('Customer ID'),
            ExportColumn::make('order_status')
                ->label('Status')
                ->formatStateUsing(fn (string $state): string => OrderStatus::tryFrom($state)?->name ?? $state),
            ExportColumn::make('amount')
                ->label('Amount'),
            ExportColumn::make('currency')
                ->label('Currency'),
            ExportColumn::make('is_paid')
                ->label('Paid')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
            ExportColumn::make('order_completed')
                ->label('Completed')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
            ExportColumn::make('country')
                ->label('Country'),
            ExportColumn::make('city')
                ->label('City'),
            ExportColumn::make('state')
                ->label('State'),
            ExportColumn::make('zip')
                ->label('ZIP'),
            ExportColumn::make('address')
                ->label('Address'),
            ExportColumn::make('address2')
                ->label('Address 2'),
            ExportColumn::make('phone')
                ->label('Phone'),
            ExportColumn::make('shipping_amount')
                ->label('Shipping Amount'),
            ExportColumn::make('discount_value')
                ->label('Discount'),
            ExportColumn::make('taxes_amount')
                ->label('Tax'),
            ExportColumn::make('items_count')
                ->label('Items Count'),
            ExportColumn::make('payment_provider')
                ->label('Payment Provider'),
            ExportColumn::make('shipping_provider')
                ->label('Shipping Provider'),
            ExportColumn::make('promo_code')
                ->label('Promo Code'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('updated_at')
                ->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return 'orders-' . date('Y-m-d-H-i-s');
    }
}
