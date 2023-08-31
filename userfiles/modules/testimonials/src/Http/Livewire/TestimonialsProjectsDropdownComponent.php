<?php

namespace MicroweberPackages\Modules\Testimonials\Http\Livewire;

use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\OptionElement;
use MicroweberPackages\Modules\Testimonials\Models\Testimonial;
use MicroweberPackages\Tag\Model\Tag;

class TestimonialsProjectsDropdownComponent extends OptionElement
{

    public string $optionKey = 'show_testimonials_per_project';

    public string $view = 'microweber-module-testimonials::livewire.projects-dropdown';

    public $search;
    public $projects = [];

    public $newProjectName = '';
    public $addNewProjectModal = false;

    public function addNewProject()
    {
        $this->addNewProjectModal = true;
    }

    public function saveNewProject()
    {
        $testimonials = new Testimonial();
        $testimonials->project_name = $this->newProjectName;
        $testimonials->name = 'My Testimonial';
        $testimonials->save();

        $this->addNewProjectModal = false;
        $this->newProjectName = '';

        $this->emit('refreshTestimonials');
        $this->renderProjects();
    }

    public function updatedSearch()
    {
        $this->renderProjects();
    }

    public function deleteProject($project)
    {
        Testimonial::where('project_name', $project)->delete();

        $this->renderProjects();
        $this->emit('refreshTestimonials');
    }

    public function selectProject($project)
    {
        $this->state['settings'][$this->optionKey] = $project;
        $this->updated();
        
        $this->renderProjects();
        $this->emit('refreshTestimonials');
    }

    public function renderProjects()
    {
        $getTestimonialsQuery = Testimonial::query();

        if ($this->search) {
            $getTestimonialsQuery->where('project_name', 'LIKE', '%' . $this->search . '%');
        }

        $getTestimonials = $getTestimonialsQuery->get();

        $this->projects = [];
        foreach ($getTestimonials as $testimonial) {
            $this->projects[$testimonial->project_name] = $testimonial->project_name;
        }

    }

    public function mount()
    {
        parent::mount();

        $this->renderProjects();
    }

}
