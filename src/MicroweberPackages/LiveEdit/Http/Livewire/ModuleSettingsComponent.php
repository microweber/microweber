<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class ModuleSettingsComponent extends AdminComponent
{
    public string $view = 'microweber-live-edit::module-settings';
    public string $moduleId = '';
    public string $moduleType = '';
    public array $settings = [];
    public array $state = [];

    public function mount()
    {
        if ($this->settings) {
            foreach ($this->settings as $key => $setting) {
                $val = get_module_option($key, $this->moduleId);
                $this->settings[$key] = $val;
            }
        }
    }

    public function updated()
    {
        dump($this->state);
    }

//    public function updatedSettings($settings)
//    {
//
//        if ($this->settings) {
//            foreach ($this->settings as $key => $setting) {
//                save_option($key, $setting, $this->moduleId);
//            }
//        }
//        $this->emit('settingsChanged', ['moduleId' => $this->moduleId, 'settings' => $this->settings]);
//
//    }

    public function render()
    {

        return view($this->view);
    }
}
