<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire;

class ModuleTemplateSelectComponent extends ModuleSettingsComponent
{


    public array $settings = [
        'template' => 'default',
    ];


    public function render()
    {

         $moduleTemplates = module_templates($this->moduleType);

         if(empty($moduleTemplates)){

             return '<div class="alert">There are no templates for this module</div>';
         }



      //  $currentTemplate = get_option('data-template', $this->moduleId);
        return view('microweber-live-edit::module-select-template', [
            'moduleTemplates' => $moduleTemplates,
            'moduleType' => $this->moduleType,
            'moduleId' => $this->moduleId,
            'settings' => $this->settings,
        ]);

    }
}
