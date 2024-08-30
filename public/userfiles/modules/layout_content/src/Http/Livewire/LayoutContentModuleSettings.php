<?php

namespace MicroweberPackages\Modules\LayoutContent\Http\Livewire;


use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorComponent;
use MicroweberPackages\Modules\LayoutContent\Models\LayoutContentItem;

class LayoutContentModuleSettings extends ModuleSettingsItemsEditorComponent
{

    public string $module = 'layout_content';

    public function getModel(): string
    {
        return LayoutContentItem::class;
    }

    public function getEditorSettings(): array
    {
        return [
            'config' => [
                'title' => '',
                'addButtonText' => 'Add Content',
                'editButtonText' => 'Edit',
                'deleteButtonText' => 'Delete',
                'sortItems' => true,
                'settingsKey' => 'settings',
                'listColumns' => [
                    'image' => [
                        'label' => 'Image',
                        'type' => 'image',
                    ],
                    'title' => [
                        'label' => 'Title',
                        'type' => 'text',
                    ],
                ],
            ],
            'schema' => [
                [
                    'type' => 'image',
                    'label' => 'Image',
                    'placeholder' => 'Image',
                    'name' => 'image',

                ],
//            [
//                'type' => 'text',
//                'label' => 'Image alt text',
//                'name' => 'imageAltText',
//                'placeholder' => 'Image alt text',
//                'help' => 'Image alt text',
//            ],
                [
                    'type' => 'text',
                    'label' => 'Title',
                    'name' => 'title',
                    'placeholder' => 'Enter title',
                    'help' => 'Enter Title',
                ],
                [
                    'type' => 'simple-text-editor',
                    'label' => 'Description',
                    'name' => 'description',
                    'placeholder' => 'Enter description',
                    'help' => 'Enter description',
                    'maxlength' => '600'
                ]
                , [
                    'type' => 'text',
                    'label' => 'Button Text',
                    'name' => 'buttonText',
                    'placeholder' => 'Enter Button Text',
                    'help' => 'Enter Button Text',
                ],
                [
                    'type' => 'link-picker',
                    'label' => 'Button Link',
                    'name' => 'buttonLink',
                    'placeholder' => 'https://yourwebsite.com',
                    'help' => 'Select Link',
                ]
            ]
        ];
    }


}
