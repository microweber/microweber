<?php

namespace Modules\Newsletter\Filament\Exports;

use Modules\Newsletter\Models\NewsletterList;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class NewsletterListExporter extends Exporter
{
    protected static ?string $model = NewsletterList::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Name'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('updated_at')
                ->label('Updated At'),
            // You might want to add a column for subscriber count if needed
            // ExportColumn::make('subscribers_count')->label('Subscribers Count')
            //    ->state(fn (NewsletterList $record): int => $record->subscribers()->count()),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your newsletter list export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

     public function getFileName(Export $export): string
     {
         return 'newsletter-lists-' . date('Y-m-d-H-i-s');
     }
}