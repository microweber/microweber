<?php

namespace MicroweberPackages\Modules\Comments\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Option\Models\Option;

class AdminSettingsModalComponent extends AdminModalComponent
{
    public $settings = [];

    public function render()
    {
        $this->refreshSettings();

        return view('comments::admin.livewire.settings-modal');
    }

    public function refreshSettings()
    {
        $getOptions = Option::where('option_group', 'comments')->get();
        if (!empty($getOptions)) {
            foreach ($getOptions as $option) {
                $this->settings[$option->option_key] = $option->option_value;
            }
        }
    }

    public function updatedSettings($value, $key)
    {
        save_option($key, $value, 'comments');

        $this->refreshSettings();
    }
}
