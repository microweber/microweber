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
      //  $currentTemplate = get_option('data-template', $this->moduleId);

        return view('live-edit::module_select_template', [
            'moduleTemplates' => $moduleTemplates,
       //     'currentTemplate' => $currentTemplate,

            'moduleType' => $this->moduleType,
            'moduleId' => $this->moduleId,
            'settings' => $this->settings,
        ]);

    }
}
