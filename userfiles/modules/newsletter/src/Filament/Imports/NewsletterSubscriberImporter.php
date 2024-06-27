<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Imports;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class NewsletterSubscriberImporter extends Importer
{
    protected static ?string $model = NewsletterSubscriber::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->rules(['max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?NewsletterSubscriber
    {
         return NewsletterSubscriber::firstOrNew([
             // Update existing records, matching them by `$this->data['column_name']`
             'email' => $this->data['email'],
         ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your newsletter subscriber import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
