<?php

namespace MicroweberPackages\Modules\Teamcard\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TeamcardListItemsComponent extends ModuleSettingsComponent
{

    public $items = [];

    public $listeners  = [
        'onItemChanged' => '$refresh',
    ];


    public function render()
    {

        $settings = get_module_option('settings', $this->moduleId);
        $json = @json_decode($settings, true);

        if ($json) {
            $this->items = $json;
        }

        return view('microweber-module-teamcard::livewire.list-teamcard-items');
    }


}
