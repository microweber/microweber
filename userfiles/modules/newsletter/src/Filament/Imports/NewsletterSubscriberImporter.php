<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Imports;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use MicroweberPackages\FormBuilder\Elements\RadioButton;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
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

    public static function getOptionsFormComponents(): array
    {
        return [
            Radio::make('select_list')
                ->label('')
                ->live()
                ->default('import_to_new_list')
                ->options([
                    'import_to_new_list' => 'Create new list and import subscribers',
                    'import_to_existing_list' => 'Import subscribers to existing list',
                ]),

            TextInput::make('new_list_name')
                ->hidden(function (Get $get){
                if ($get('select_list') == 'import_to_new_list') {
                    return false;
                }
                return true;
            }),

            CheckboxList::make('lists')
                ->hidden(function (Get $get){
                    if ($get('select_list') == 'import_to_existing_list') {
                        return false;
                    }
                    return true;
                })
                ->label('Import subscribers to list')
                ->options(NewsletterList::all()->pluck('name', 'id')->toArray()),
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
