<?php
namespace MicroweberPackages\Modules\Testimonials\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TestimonialsSettingsComponent extends ModuleSettingsComponent
{
    public $editorSettings = [];

    public function render()
    {
        $this->editorSettings = $this->getEditorSettings();

       return view('microweber-module-testimonials::livewire.settings');
    }


    public function getEditorSettings()
    {

        $editorSettings = [
            'config' => [
                'title' => '',
                'addButtonText' => 'Add Testimonial',
                'editButtonText' => 'Edit',
                'deleteButtonText' => 'Delete',
                'sortItems' => true,
                'settingsKey' => 'settings',
                'listColumns' => [
                    'name' => 'name',
                ],
            ],
            'schema' => [
                [
                    'type' => 'text',
                    'rules' => 'required|min:2|max:255',
                    'label' => 'Name',
                    'name' => 'name',
                    'placeholder' => 'Name',
                    'help' => 'Name is required'
                ],
                [
                  'type' => 'textarea',
                    'rules' => 'required|min:2|max:255',
                    'label' => 'Content',
                    'name' => 'content',
                    'placeholder' => 'Content',
                    'help' => 'Content is required'
                ],
                [
                    'type' => 'text',
                    'rules' => 'required|min:2|max:255',
                    'label' => 'Read more URL',
                    'name' => 'read_more_url',
                    'placeholder' => 'Read more URL',
                    'help' => 'Read more URL is required'
                ],

            ]
        ];

//        'id' => "integer",
//        'name' => "text",
//        'content' => "text",
//        'read_more_url' => "text",
//        'created_on' => "datetime",
//		'project_name' => "text",
//	    'client_company' => "text",
//		'client_role' => "text",
//		'client_picture' => "text",
//		'client_website' => "text",
//        'position' => "integer"
        return $editorSettings;
    }

}
