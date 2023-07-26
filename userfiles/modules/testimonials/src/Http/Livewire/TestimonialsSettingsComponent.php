<?php
namespace MicroweberPackages\Modules\Testimonials\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TestimonialsSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-testimonials::livewire.settings');
    }
}
