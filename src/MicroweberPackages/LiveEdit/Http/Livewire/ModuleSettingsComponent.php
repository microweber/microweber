<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class ModuleSettingsComponent extends AdminComponent
{
    public string $view = 'microweber-live-edit::module-settings';
    public string $moduleId = '';
    public string $moduleType = '';
    public $moduleParams = [];
    public array $settings = [

    ];
    public array $state = [

    ];
    public array $multilanguage = [

    ];

    public function mount()
    {
        if ($this->settings) {
            foreach ($this->settings as $key => $setting) {
                $val = get_module_option($key, $this->moduleId);
                $this->settings[$key] = $val;
                $this->state['settings'][$key] = $val;
            }
        }
    }



    public function updatedSettings($settings)
    {
        if ($this->settings) {
            foreach ($this->settings as $key => $setting) {
                $this->saveModuleOption($key, $setting, $this->moduleId);
            }
        }
        $this->emit('settingsChanged', ['moduleId' => $this->moduleId, 'moduleType' => $this->moduleType, 'settings' => $settings]);

    }


    public function saveModuleOption($key, $value, $moduleId = false,$lang = false){
        $option = array();
        $option['option_value'] = $value;
        $option['option_key'] = $key;
        $option['option_group'] = $moduleId;
        if($lang){
            $option['lang'] = $lang;
        }

        return app()->option_manager->save($option);
    }


    public function render()
    {

        return view($this->view);
    }
}
