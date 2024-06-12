<?php

namespace MicroweberPackages\Modules\Teamcard\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorComponent;
use MicroweberPackages\Modules\Faq\Models\FaqItem;
use MicroweberPackages\Modules\Teamcard\Models\TeamcardItem;

class TeamcardModuleSettings extends ModuleSettingsItemsEditorComponent
{
    public string $module = 'teamcard';

    public function getModel(): string
    {
        return TeamcardItem::class;
    }

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
                    'file' => 'Image',
                    'name' => 'Name',
                ],
            ],
            'schema' => [
                [
                    'type' => 'text',
                    'label' => 'Team member name',
                    'name' => 'name',
                    'placeholder' => 'Enter name',
                    'help' => 'Enter Name',
                ],
                [
                    'type' => 'image',
                    'label' => 'Team member picture',
                    'placeholder' => 'Team member picture',
                    'name' => 'file',

                ], [
                    'type' => 'textarea',
                    'label' => 'Team member bio',
                    'name' => 'bio',
                    'placeholder' => 'Enter bio',
                    'help' => 'Enter bio',
                    'maxlength' => '150'
                ]
                , [
                    'type' => 'text',
                    'label' => 'Team member role',
                    'name' => 'role',
                    'placeholder' => 'Enter role',
                    'help' => 'Enter role',
                ]
                , [
                    'type' => 'url',
                    'label' => 'Team member website',
                    'name' => 'website',
                    'placeholder' => 'https://yourwebsite.com',
                    'help' => 'Enter website',
                ]
            ]
        ];
    }


}
