<?php

namespace MicroweberPackages\Modules\Tabs\Filament\Admin;

use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorComponent;
use MicroweberPackages\Modules\Tabs\Models\TabItem;

class TabsModuleSettings extends ModuleSettingsItemsEditorComponent
{

    public string $module = 'tabs';

    protected static string $view = 'microweber-module-tabs::livewire.tabs-module-settings';


    public function getModel(): string
    {
        return TabItem::class;
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
                    'title' => [
                        'label' => 'Title',
                        'type' => 'text'
                    ]
                ],
            ],
            'schema' => [
                [
                    'type' => 'text',
                    'label' => 'Title',
                    'name' => 'title',
                    'placeholder' => 'Enter your tab title',
                    'help' => 'Title',
                ],
                [
                    'type' => 'text',
                    'label' => 'Tab icon',
                    'name' => 'icon',
                    'placeholder' => 'Tab icon',
                    'help' => 'Tab icon',
                ]
            ]
        ];
    }
}
