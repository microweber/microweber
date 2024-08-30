<?php
namespace MicroweberPackages\Modules\TweetEmbed\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TweetEmbedSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-tweet-embed::livewire.settings');
    }
}
