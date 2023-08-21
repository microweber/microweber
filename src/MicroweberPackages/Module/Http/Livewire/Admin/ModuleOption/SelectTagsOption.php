<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption;

use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Tag\Model\Tag;

class SelectTagsOption extends OptionElement
{
    public string $view = 'module::admin.option.select-tags';

    public $search;
    public $tags = [];
    public $selectedTags = [];

    public function updatedSearch()
    {
        $this->renderTags();
    }

    public function appendTag($tag)
    {
        $this->selectedTags[$tag] = $tag;

        if (!empty($this->selectedTags)) {
            $this->state['settings'][$this->optionKey] = implode(',', $this->selectedTags);
            $this->updated();
        }

        $this->renderTags();
    }

    public function removeTag($tag)
    {
        unset($this->selectedTags[$tag]);

        if (!empty($this->selectedTags)) {
            $this->state['settings'][$this->optionKey] = implode(',', $this->selectedTags);
        } else {
            $this->state['settings'][$this->optionKey] = null;
        }
        
        $this->updated();
    }

    public function renderTags()
    {
        $getTagsQuery = Tag::query();

        if ($this->search) {
            $getTagsQuery->where('name', 'LIKE', '%' . $this->search . '%');
        }

        $getTags = $getTagsQuery->get();

        $this->tags = [];
        foreach ($getTags as $tag) {
            if (isset($this->selectedTags[$tag->name])) {
                continue;
            }
            $this->tags[$tag->id] = $tag->name;
        }
    }

    public function mount()
    {
        parent::mount();

        if (isset($this->state['settings']) and isset($this->state['settings'][$this->optionKey])) {
            $selectedOption = $this->state['settings'][$this->optionKey];
            $selectedOption = explode(',', $selectedOption);
            if (!empty($selectedOption)) {
                foreach ($selectedOption as $option) {
                    $this->selectedTags[$option] = $option;
                }
            }
        }

        $this->renderTags();
    }
}
