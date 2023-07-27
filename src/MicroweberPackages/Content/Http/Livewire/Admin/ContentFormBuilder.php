<?php

namespace MicroweberPackages\Content\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class ContentFormBuilder extends AdminComponent
{

    public $itemState = [];
    public $editorSettings = [];


    public function mount()
    {
        $this->pupulateEditorSchema();
    }

    public function pupulateEditorSchema()
    {

        $editorSettings = [
            'config' => [
                'title' => 'Edit post',

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
                    'type' => 'textarea',
                    'label' => 'Content body',
                    'name' => 'content_body',
                ]

            ]
        ];
        $this->editorSettings = $editorSettings;

    }

    public function updateContent()
    {
        $this->validate();

        $content = \MicroweberPackages\Content\Models\Content::find($this->contentData['id']);
        //   $content->title = $this->contentData['title'];
        $content->save();

        $this->emit('content.updated');
    }

    public function render()
    {

        return view('content::admin.content.livewire.form-builder.main');

    }


}
