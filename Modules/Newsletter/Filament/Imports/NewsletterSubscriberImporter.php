<?php

namespace Modules\Newsletter\Filament\Imports;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use MicroweberPackages\FormBuilder\Elements\RadioButton;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;

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
                    'import_to_existing_list' => 'Restore subscribers to existing list',
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
                ->label('Restore subscribers to list')
                ->options(NewsletterList::all()->pluck('name', 'id')->toArray()),

            Checkbox::make('skip_existing')
                ->label('Skip if email exists in any other list')
                ->default(false),
        ];
    }

    public function resolveRecord(): ?NewsletterSubscriber
    {
        if (!isset($this->data['email']) || empty($this->data['email'])) {
            return null;
        }

        $listIds = [];

        if (isset($this->options['select_list'])) {
            if(array_key_exists('new_list_name', $this->options)) {
                if(!($this->options['new_list_name'])) {
                    $this->options['new_list_name'] = 'New List ' . date('Y-m-d') . ' ' . uniqid();
                }
            }

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

        // Check if subscriber exists and skip_existing is enabled
        if ($findSubscriber && isset($this->options['skip_existing']) && $this->options['skip_existing']) {
            // Get all lists where this email exists
            $existingListIds = NewsletterSubscriberList::where('subscriber_id', $findSubscriber->id)
                ->pluck('list_id')
                ->toArray();

            // If email exists in any list other than the selected ones, skip it
            $existingInOtherLists = array_diff($existingListIds, is_array($listIds) ? $listIds : [$listIds]);
            if (!empty($existingInOtherLists)) {
                return null;
            }
        }

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
