<?php

namespace Modules\Product\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Modules\Product\Models\Product;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('title')
                ->label('Title'),
            ExportColumn::make('url')
                ->label('URL Slug'),
            ExportColumn::make('sku')
                ->label('SKU'),
            ExportColumn::make('price')
                ->label('Price'),
            ExportColumn::make('qty')
                ->label('Quantity'),
            ExportColumn::make('is_active')
                ->label('Active')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
            ExportColumn::make('description')
                ->label('Description'),
            ExportColumn::make('content_meta_title')
                ->label('Meta Title'),
            ExportColumn::make('content_meta_keywords')
                ->label('Meta Keywords'),
            ExportColumn::make('content_meta_description')
                ->label('Meta Description'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('updated_at')
                ->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your product export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return 'products-' . date('Y-m-d-H-i-s');
    }
}
