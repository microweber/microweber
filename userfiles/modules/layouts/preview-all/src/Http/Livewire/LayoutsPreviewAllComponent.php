<?php
namespace MicroweberPackages\Modules\Layouts\PreviewAll\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class LayoutsPreviewAllComponent extends ModuleSettingsComponent
{
    public $layoutCategory = 'Gallery';
    public $layouts = [];

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
            $this->layouts = $layoutCategories[$this->layoutCategory];
        }

    }

    public function mount()
    {
        $this->renderLayouts();
    }

    public function render()
    {
       return view('microweber-module-layouts-preview-all::livewire.preview-all');
    }
}
