<?php

namespace MicroweberPackages\Modules\Marquee\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class MarqueeSettingsComponent extends ModuleSettingsComponent
{

    public function render()
    {
        return view('microweber-module-marquee::livewire.settings');
    }
}
