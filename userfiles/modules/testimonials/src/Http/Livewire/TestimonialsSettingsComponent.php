<?php
namespace MicroweberPackages\Modules\Testimonials\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
use MicroweberPackages\Modules\Testimonials\Models\Testimonial;
use PHPUnit\Util\Test;

class TestimonialsSettingsComponent extends ModuleSettingsComponent
{
    public $items = [];
    public $projectNames = [];
    public $editorSettings = [];

    public $selectedItemsIds = [];
    public $areYouSureDeleteModalOpened = false;

    public array $itemState = [];

    public $listeners = [
        'refreshTestimonials' => '$refresh',
        'onShowConfirmDeleteItemById' => 'showConfirmDeleteItemById',
        'onEditItemById' => 'showItemById',
    ];

    public function render()
    {
       $this->getItems();
        $this->editorSettings = $this->getEditorSettings();

       return view('microweber-module-testimonials::livewire.settings');
    }

    public function showConfirmDeleteItemById($itemId)
    {
        $this->areYouSureDeleteModalOpened = true;
        $this->selectedItemsIds = [$itemId];
    }

    public function confirmDeleteSelectedItems()
    {

        if ($this->selectedItemsIds and !empty($this->selectedItemsIds)) {
            foreach ($this->selectedItemsIds as $itemId) {
                Testimonial::where('id', $itemId)->delete();
            }
        }

        $this->areYouSureDeleteModalOpened = false;
        $this->selectedItemsIds = [];
        $this->getItems();

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
                [
                    'type' => 'text',
                    'rules' => 'required|min:2|max:255',
                    'label' => 'Created on',
                    'name' => 'created_on',
                    'placeholder' => 'Created on',
                    'help' => 'Created on is required'
                ],
                [
                    'type'=>'text',
                    'rules'=>'required|min:2|max:255',
                    'label'=>'Project name',
                    'name'=>'project_name',
                    'placeholder'=>'Project name',
                    'help'=>'Project name is required'
                ],
                [
                    'type'=>'text',
                    'rules'=>'required|min:2|max:255',
                    'label'=>'Client company',
                    'name'=>'client_company',
                    'placeholder'=>'Client company',
                    'help'=>'Client company is required'
                ],
                [
                    'type'=>'text',
                    'rules'=>'required|min:2|max:255',
                    'label'=>'Client role',
                    'name'=>'client_role',
                    'placeholder'=>'Client role',
                    'help'=>'Client role is required'
                ],
                [
                    'type'=>'image',
                    'rules'=>'required|min:2|max:255',
                    'label'=>'Client picture',
                    'name'=>'client_picture',
                    'placeholder'=>'Client picture',
                    'help'=>'Client picture is required'
                ],
                [
                    'type'=>'text',
                    'rules'=>'required|min:2|max:255',
                    'label'=>'Client website',
                    'name'=>'client_website',
                    'placeholder'=>'Client website',
                    'help'=>'Client website is required'
                ]
            ]
        ];
        return $editorSettings;
    }

    public function submit()
    {
        $rules = [];
        $schema = $this->getEditorSettings()['schema'];

        foreach ($schema as $field) {
            if (isset($field['name']) && isset($field['rules'])) {
                $rules['itemState.' . $field['name']] = $field['rules'];
            }
        }
        $this->validate($rules);

        $testimonial = new Testimonial();
        $testimonial->name = $this->itemState['name'];
        $testimonial->content = $this->itemState['content'];
        $testimonial->read_more_url = $this->itemState['read_more_url'];
        $testimonial->created_on = $this->itemState['created_on'];
        $testimonial->project_name = $this->itemState['project_name'];
        $testimonial->client_company = $this->itemState['client_company'];
        $testimonial->client_role = $this->itemState['client_role'];
        $testimonial->client_picture = $this->itemState['client_picture'];
        $testimonial->client_website = $this->itemState['client_website'];
        $testimonial->save();

        $this->emit('switchToMainTab');
        $this->emit('settingsChanged', ['moduleId' => $this->moduleId]);

        return $this->render();
    }

    public function getItems()
    {
        $this->items = [];
        $getTestimonials = Testimonial::all();
        if ($getTestimonials) {
            foreach ($getTestimonials as $testimonial) {
                $this->projectNames[$testimonial->project_name] = $testimonial->project_name;
            }
        }

        $getTestimonialsQuery = Testimonial::query();

        $filterProject = get_option('show_testimonials_per_project', $this->moduleId);
        if (!empty($filterProject)) {
            $getTestimonialsQuery->where('project_name', $filterProject);
        }

        $getTestimonials = $getTestimonialsQuery->get();

        if ($getTestimonials->count() > 0) {
            foreach ($getTestimonials as $testimonial) {
                $this->items[] = $testimonial->toArray();
            }
        }
    }

}
