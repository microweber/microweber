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
        $this->editorSettings = $editorSettings;

    }

    public function submit()
    {



//        $this->validate();
//
//        $content = \MicroweberPackages\Content\Models\Content::find($this->contentData['id']);
//        //   $content->title = $this->contentData['title'];
//        $content->save();


        $saveContent = [];
        if(!empty($this->itemState )){
            $saveContent = $this->itemState;
            $save = save_content($saveContent);

            dump($save);
        }

        $this->emit('content.updated');
    }

    public function render()
    {

        return view('content::admin.content.livewire.form-builder.main');

    }


}
