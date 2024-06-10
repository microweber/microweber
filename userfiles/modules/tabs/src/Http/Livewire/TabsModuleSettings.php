<?php

namespace MicroweberPackages\Modules\Tabs\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorComponent;
use MicroweberPackages\Modules\Tabs\Models\Tab;

class TabsModuleSettings extends ModuleSettingsItemsEditorComponent
{

    public string $module = 'tabs';

    protected static string $view = 'microweber-module-tabs::livewire.tabs-module-settings';

    public function getEditorSettings() : array
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
                    'title' => 'Title',
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
                    'type' => 'icon',
                    'label' => 'Tab icon',
                    'name' => 'icon',
                    'placeholder' => 'Tab icon',
                    'help' => 'Tab icon',
                ]
            ]
        ];
    }
}
