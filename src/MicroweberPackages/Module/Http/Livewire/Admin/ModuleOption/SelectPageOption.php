<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption;

use MicroweberPackages\Page\Models\Page;

class SelectPageOption extends OptionElement
{
    public string $view = 'module::admin.option.select-page';

    public $search;
    public $pagesTree = [];

    public function updatedSearch()
    {
        $this->renderPagesTree();
    }

    public function selectPage($pageId)
    {
        $this->state['settings'][$this->optionKey] = $pageId;
        $this->updated();
    }

    public function renderPagesTree()
    {
        $getPageQuery = Page::query();

        if ($this->search) {
            $getPageQuery->where('title', 'LIKE', '%' . $this->search . '%');
        }

        $getPages = $getPageQuery->get();

        $this->pagesTree = [];
        foreach ($getPages as $page) {
            $this->pagesTree[$page->id] = $page->title;
        }
    }

    public function mount()
    {
        parent::mount();
        $this->renderPagesTree();
    }

}
