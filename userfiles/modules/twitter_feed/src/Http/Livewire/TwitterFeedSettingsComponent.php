<?php
namespace MicroweberPackages\Modules\TwitterFeed\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TwitterFeedSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-twitter-feed::livewire.settings');
    }
}
