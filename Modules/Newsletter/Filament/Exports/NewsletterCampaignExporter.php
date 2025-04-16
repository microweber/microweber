<?php

namespace Modules\Newsletter\Filament\Exports;

use Modules\Newsletter\Models\NewsletterCampaign;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class NewsletterCampaignExporter extends Exporter
{
    protected static ?string $model = NewsletterCampaign::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Name'),
            ExportColumn::make('list.name') // Assuming 'list' is the relationship name
                ->label('List Name'),
            ExportColumn::make('senderAccount.from_name') // Assuming 'senderAccount' is the relationship
                ->label('Sender Name'),
             ExportColumn::make('senderAccount.from_email')
                 ->label('Sender Email'),
            ExportColumn::make('subject')
                ->label('Subject'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('scheduled_at')
                ->label('Scheduled At'),
            ExportColumn::make('sent_at')
                ->label('Sent At'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('updated_at')
                ->label('Updated At'),
            ExportColumn::make('subscribers')
                ->label('Total Subscribers'),
             ExportColumn::make('opened')
                 ->label('Opened Count'),
             ExportColumn::make('clicked')
                 ->label('Clicked Count'),
            // Add more columns as needed, e.g., open rate, click rate
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your newsletter campaign export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

     public function getFileName(Export $export): string
     {
         return 'newsletter-campaigns-' . date('Y-m-d-H-i-s');
     }
}