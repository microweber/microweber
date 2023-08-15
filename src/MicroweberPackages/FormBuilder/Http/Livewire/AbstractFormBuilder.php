<?php

namespace MicroweberPackages\FormBuilder\Http\Livewire;


use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\FormBuilder\Repositories\FormItemsRepositoryInterface;

class AbstractFormBuilder extends AdminComponent
{

    public $items = [];
    public $item = null;

    public $view = 'microweber-form-builder::livewire.form-builder.main';

    public FormItemsRepositoryInterface $itemsRepository;


    public function getFormConfig(): array
    {

        $editorSettings = [
            'config' => [
                'title' => 'Accordion',
                'addButtonText' => 'Add accordion item',
                'editButtonText' => 'Edit accordion item',
                'deleteButtonText' => 'Delete accordion item',
                'sortItems' => true,
                'settingsKey' => 'settings',
                'listColumns' => [
                    'icon' => 'icon',
                    'title' => 'title',
                ],
            ],
            'schema' => [
                [
                    'type' => 'text',
                    'label' => 'Title',
                    'name' => 'title',
                    'placeholder' => 'Enter title',
                    'help' => 'Enter title',
                ],
                [
                    'type' => 'icon',
                    'label' => 'Icon',
                    'placeholder' => 'Icon',
                    'name' => 'icon',
                ]

            ]
        ];

        return $editorSettings;
    }


    public function render()
    {
        return view($this->view);
    }


}
