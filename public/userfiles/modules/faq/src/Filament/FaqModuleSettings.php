<?php

namespace MicroweberPackages\Modules\Faq\Filament;

use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorComponent;
use MicroweberPackages\Modules\Faq\Models\FaqItem;

class FaqModuleSettings extends ModuleSettingsItemsEditorComponent
{
    public string $module = 'faq';

    public function getModel(): string
    {
        return FaqItem::class;
    }

    public function getEditorSettings(): array
    {
        return [
            'config' => [
                'title' => '',
                'addButtonText' => 'Add Item',
                'editButtonText' => 'Edit',
                'deleteButtonText' => 'Delete',
                'sortItems' => true,
                'settingsKey' => 'settings',
                'listColumns' => [
                    'question' => [
                        'type' => 'text',
                        'label' => 'Question',
                    ]
                ],
            ],


            'schema' => [
                [
                    'type' => 'text',
                    'label' => 'Question',
                    'name' => 'question',
                    'placeholder' => 'Enter question',
                    'help' => 'Enter question',
                ],
                [
                    'type' => 'textarea',
                    'label' => 'Answer',
                    'name' => 'answer',
                    'placeholder' => 'Enter answer',
                    'help' => 'Enter answer',
                ]
            ]
        ];
    }


}
