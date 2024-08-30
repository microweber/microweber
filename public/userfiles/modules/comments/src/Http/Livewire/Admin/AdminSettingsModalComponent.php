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

    public function toggleSettings($key)
    {
        $value = 'y';
        if (isset($this->settings[$key]) and $this->settings[$key] == 'y') {
            $value = 'n';
        }

        save_option($key, $value, 'comments');
    }
}
