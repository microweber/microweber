<?php
namespace MicroweberPackages\Modules\Faq\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
/**
 * @deprecated
 */
class FaqSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-faq::livewire.settings');
    }
}
