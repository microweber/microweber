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
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriberList;

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

        if (!isset($this->data['email']) || empty($this->data['email'])) {
            return null;
        }

        $listIds = [];
        if (isset($this->options['select_list'])) {
            if (isset($this->options['new_list_name']) && $this->options['select_list'] == 'import_to_new_list') {
                $findList = NewsletterList::where('name', $this->options['new_list_name'])->first();
                if (!$findList) {
                    $list = new NewsletterList();
                    $list->name = $this->options['new_list_name'];
                    $list->save();
                    $listIds[] = $list->id;
                } else {
                    $listIds[] = $findList->id;
                }
            } else {
                $listIds = $this->options['lists'];
            }
        }

        $findSubscriber = NewsletterSubscriber::where('email', $this->data['email'])->first();
        if (!$findSubscriber) {
            $findSubscriber = new NewsletterSubscriber();
            $findSubscriber->email = $this->data['email'];
            $findSubscriber->save();
        }

        if (!empty($listIds)) {
            foreach($listIds as $listId) {
                $findSubscriberList = NewsletterSubscriberList::where('subscriber_id', $findSubscriber->id)
                    ->where('list_id', $listId)
                    ->first();
                if (!$findSubscriberList) {
                    $subscriberList = new NewsletterSubscriberList();
                    $subscriberList->subscriber_id = $findSubscriber->id;
                    $subscriberList->list_id = $listId;
                    $subscriberList->save();
                }
            }
        }

        return $findSubscriber;
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
