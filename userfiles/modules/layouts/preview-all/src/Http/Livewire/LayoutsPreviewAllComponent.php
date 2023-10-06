<?php
namespace MicroweberPackages\Modules\Layouts\PreviewAll\Http\Livewire;

use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;
use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class LayoutsPreviewAllComponent extends ModuleSettingsComponent
{
    use WithPagination;

    public $listeners = [
      'layoutsPreviewAllFilter'=>'$refresh'
    ];

    public $layoutCategory = 'Animated Backgrounds';
    public $filteredLayouts = [];

    public function renderLayouts()
    {
        $layoutCategories = [];
        $moduleTemplates = module_templates('layouts');
        foreach ($moduleTemplates as $moduleTemplate) {
            $category = 'All';
            if (isset($moduleTemplate['categories'])) {
                $category = $moduleTemplate['categories'];
            }
            $layoutCategories[$category][] = $moduleTemplate;
        }

        if (isset($layoutCategories[$this->layoutCategory])) {
            $this->filteredLayouts = $layoutCategories[$this->layoutCategory];
        }

    }

    public function mount()
    {
        $this->renderLayouts();
    }

    public function render()
    {

        $perPage = 5;

        $collection = collect($this->filteredLayouts);

        $offset = max(0, ($this->page - 1) * $perPage);
        $items = $collection->slice($offset, $perPage + 1);
        $paginator = new Paginator($items, $perPage, $this->page);

       return view('microweber-module-layouts-preview-all::livewire.preview-all', [
           'paginator'=>$paginator,
            'layouts'=>$items
       ]);

    }
}
