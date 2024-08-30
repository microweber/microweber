<?php
namespace MicroweberPackages\Modules\SliderV2\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorComponent;
use MicroweberPackages\Modules\SliderV2\Models\SliderItem;
use MicroweberPackages\Modules\Teamcard\Models\TeamcardItem;

class SliderSettingsComponent extends ModuleSettingsItemsEditorComponent
{
    public string $module = 'slider_v2';

    public function getModel(): string
    {
        return SliderItem::class;
    }

    public function getEditorSettings() : array
    {
        return [
            'config' => [
                'title' => '',
                'addButtonText' => 'Add Slide',
                'editButtonText' => 'Edit',
                'deleteButtonText' => 'Delete',
                'sortItems' => true,
                'settingsKey' => 'settings',
                'listColumns' => [
                    'image'=>[
                        'label' => 'Image',
                        'type' => 'image',
                    ],
                    'title'=>[
                        'label' => 'Title',
                        'type' => 'text',
                    ],
                ],
                'realtimeEditing' => true,
            ],
            'schema' => [
                [
                    'type' => 'image',
                    'label' => 'Image',
                    'name' => 'image',
                    'placeholder' => 'Image',
                    'help' => 'Image',
                ],
                [
                    'type' => 'text',
                    'label' => 'Slide Title',
                    'name' => 'title',
                    'placeholder' => 'Slide Title',
                    'help' => 'Slide Title',
                ],
//            [
//                'type' => 'icon',
//                'label' => 'Icon',
//                'name' => 'icon',
//                'placeholder' => 'Icon',
//                'help' => 'Icon',
//            ],

                [
                    'type' => 'textarea',
                    'label' => 'Slide Description',
                    'name' => 'description',
                    'placeholder' => 'Slide Description',
                    'help' => 'Slide Description',
                ],
                [
                    'type' => 'select',
                    'label' => 'Align Items',
                    'name' => 'alignItems',
                    'placeholder' => 'Align Items',
                    'help' => 'Align Items',
                    'options' => [
                        'left' => 'Left',
                        'center' => 'Center',
                        'right' => 'Right',
                    ],
                ],
                [
                    'type' => 'toggle',
                    'label' => 'Show button',
                    'name' => 'showButton',
                ],
                [
                    'type' => 'text',
                    'label' => 'Button text',
                    'name' => 'buttonText',
                    'placeholder' => 'Button text',
                    'help' => 'Button text',
                ],
                [
                    'type' => 'color',
                    'label' => 'Button Background color',
                    'name' => 'buttonBackgroundColor',
                    'placeholder' => 'Button background color',
                    'help' => 'Button background color',
                    'showIf' => [
                        'showButton' => '1'
                    ]
                ],
                [
                    'type' => 'color',
                    'label' => 'Button Background Hover color',
                    'name' => 'buttonBackgroundHoverColor',
                    'placeholder' => 'Button background hover color',
                    'help' => 'Button background hover color',
                    'showIf' => [
                        'showButton' => '1'
                    ]
                ],
                [
                    'type' => 'color',
                    'label' => 'Button Border color',
                    'name' => 'buttonBorderColor',
                    'placeholder' => 'Button border color',
                    'help' => 'Button border color',
                    'showIf' => [
                        'showButton' => '1'
                    ]
                ],
                [
                    'type' => 'color',
                    'label' => 'Button text color',
                    'name' => 'buttonTextColor',
                    'placeholder' => 'Button text color',
                    'help' => 'Button text color',
                    'showIf' => [
                        'showButton' => '1'
                    ]
                ],
                [
                    'type' => 'color',
                    'label' => 'Button text hover color',
                    'name' => 'buttonTextHoverColor',
                    'placeholder' => 'Button text hover color',
                    'help' => 'Button text hover color',
                    'showIf' => [
                        'showButton' => '1'
                    ]
                ],
                [
                    'type' => 'range',
                    'label' => 'Button Font Size',
                    'name' => 'buttonFontSize',
                    'placeholder' => 'Button Font Size',
                    'help' => 'Button Font Size',
                    'min' => 8,
                    'max' => 64,
                    'labelUnit' => 'px',
                    'showIf' => [
                        'showButton' => '1'
                    ]
                ],
                [
                    'type' => 'link-picker',
                    'label' => 'Button url',
                    'name' => 'url',
                    'placeholder' => 'Button url',
                    'help' => 'Button url',
                    'showIf' => [
                        'showButton' => '1'
                    ]
                ],
                [
                    'type' => 'color',
                    'label' => 'Title color',
                    'name' => 'titleColor',
                    'placeholder' => 'Title color',
                    'help' => 'Title color',
                ],
                [
                    'type' => 'range',
                    'label' => 'Title Font Size',
                    'name' => 'titleFontSize',
                    'placeholder' => 'Title Font Size',
                    'help' => 'Title Font Size',
                    'min' => 8,
                    'max' => 64,
                    'labelUnit' => 'px',
                ],
                [
                    'type' => 'color',
                    'label' => 'Description color',
                    'name' => 'descriptionColor',
                    'placeholder' => 'Description color',
                    'help' => 'Description color',
                ],
                [
                    'type' => 'range',
                    'label' => 'Description Font Size',
                    'name' => 'descriptionFontSize',
                    'placeholder' => 'Description Font Size',
                    'help' => 'Description Font Size',
                    'min' => 8,
                    'max' => 64,
                    'labelUnit' => 'px',
                ],
                [
                    'type' => 'color',
                    'label' => 'Image Background Color',
                    'name' => 'imageBackgroundColor',
                    'placeholder' => 'Image Background Color',
                    'help' => 'Image Background Color',
                ],
                [
                    'type' => 'range',
                    'label' => 'Image Background Opacity',
                    'name' => 'imageBackgroundOpacity',
                    'placeholder' => 'Image Background Opacity',
                    'help' => 'Image Background Opacity',
                    'min' => 0,
                    'max' => 1,
                    'labelUnit' => '%',
                ],
                [
                    'type' => 'select',
                    'label' => 'Image Background Filter',
                    'name' => 'imageBackgroundFilter',
                    'placeholder' => 'Image Background Filter',
                    'help' => 'Image Background Filter',
                    'options' => [
                        'none' => 'None',
                        'blur' => 'Blur',
                        'mediumBlur' => 'Medium Blur',
                        'maxBlur' => 'Max Blur',
                        'grayscale' => 'Grayscale',
                        'hue-rotate' => 'Hue Rotate',
                        'invert' => 'Invert',
                        'sepia' => 'Sepia',
                    ],
                ],

            ]
        ];
    }
}
