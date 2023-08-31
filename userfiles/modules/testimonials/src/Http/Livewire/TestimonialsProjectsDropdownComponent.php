<?php

namespace MicroweberPackages\Modules\Testimonials\Http\Livewire;

use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\OptionElement;
use MicroweberPackages\Modules\Testimonials\Models\Testimonial;
use MicroweberPackages\Tag\Model\Tag;

class TestimonialsProjectsDropdownComponent extends OptionElement
{
    // show_testimonials_per_project

    public string $view = 'microweber-module-testimonials::livewire.projects-dropdown';

    public $search;
    public $projects = [];

    public $addNewProjectModal = false;

    public function addNewProject()
    {
        $this->addNewProjectModal = true;
    }

    public function updatedSearch()
    {
        $this->renderProjects();
    }

    public function selectProject($project)
    {

        dd($project);

        if (!empty($this->selectedTags)) {
            $this->state['settings'][$this->optionKey] = implode(',', $this->selectedTags);
            $this->updated();
        }

        $this->renderProjects();
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
