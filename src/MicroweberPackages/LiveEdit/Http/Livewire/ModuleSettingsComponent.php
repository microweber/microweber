<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire;

use Livewire\Component;

class ModuleSettingsComponent extends Component
{
    public string $moduleId = '';

    public array $settings = [

    ];

    public function updatedSettings($settings)
    {

        if ($this->settings) {
            foreach ($this->settings as $key => $setting) {
                save_option($key, $setting, $this->moduleId);
            }
        }
        $this->emit('settingsChanged', ['moduleId' => $this->moduleId, 'settings' => $this->settings]);

    }
}
