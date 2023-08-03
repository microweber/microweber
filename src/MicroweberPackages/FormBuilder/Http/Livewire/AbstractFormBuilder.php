<?php

namespace MicroweberPackages\FormBuilder\Http\Livewire;


use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class AbstractFormBuilder extends AdminComponent
{
    public $model = null;

    public function getFormConfig(): array
    {
        $editorSettings = [
            'config' => [
                'title' => 'Edit post',

            ],
            'schema' => [
                [
                    'type' => 'text',
                    'rules' => 'required|min:2|max:255',
                    'label' => 'Title',
                    'name' => 'title',
                    'placeholder' => 'Enter title',
                    'help' => 'Enter title',
                ], [
                    'type' => 'slug',
                    'rules' => 'required|min:2|max:255',
                    'label' => 'Title',
                    'name' => 'title',
                    'placeholder' => 'Enter title',
                    'help' => 'Enter title',
                ],
                [
                    'type' => 'textarea-multilanguage',
                    'rules' => 'required|min:2|max:255',
                    'label' => 'Title',
                    'name' => 'title',
                    'placeholder' => 'Enter title',
                    'help' => 'Enter title',
                ],

                [
                    'type' => 'text',
                    'value' => 'post',
                    'name' => 'content_type',
                    'label' => 'content_type',
                ],

                [
                    'type' => 'text',
                    'value' => 'post',
                    'name' => 'content_subtype',
                    'label' => 'content_subtype',

                ],
                [
                    'type' => 'textarea',
                    'label' => 'Content body',
                    'name' => 'content_body',
                    'label' => 'content_body',
                ]

            ]
        ];

        return $editorSettings;

    }
}
