<?php

namespace MicroweberPackages\Modules\ContactForm\Http\Livewire;

use LivewireUI\Modal\ModalComponent;

class SettingsModalComponent extends ModalComponent
{
    public function render()
    {
        return view('contact-form::admin.contact-form.modals.settings-modal');
    }
}
