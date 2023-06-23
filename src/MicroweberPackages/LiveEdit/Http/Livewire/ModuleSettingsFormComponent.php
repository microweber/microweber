<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire;

class ModuleSettingsFormComponent extends ModuleSettingsComponent
{
    public string $view = 'microweber-live-edit::module-settings-form';
    public $moduleTitle = 'Module Settings';
    public array $settingsForm = [
//        'data-address' => [
//            'type' => 'text',
//            'translatable' => true,
//            'label' => 'Address',
//            'help' => 'Enter address',
//            'placeholder' => 'Enter address',
//        ],
//        'data-zoom' => [
//            'type' => 'slider',
//            'label' => 'Zoom',
//            'help' => 'Enter zoom',
//            'min' => '1',
//            'max' => '10',
//            'placeholder' => 'Enter zoom',
//        ],
    ];


    public function getSettingsForm()
    {
        return $this->settingsForm;

    }

    public function mount()
    {

        $form = $this->getSettingsForm();
        if ($form) {
            foreach ($form as $key => $item) {
                $this->settings[$key] = get_module_option($key, $this->moduleId);
            }
        }

    }

}
