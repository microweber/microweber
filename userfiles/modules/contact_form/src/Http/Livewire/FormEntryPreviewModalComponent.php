
<?php

namespace MicroweberPackages\Modules\ContactForm\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class FormEntryPreviewModalComponent extends AdminComponent
{


    public function render()
    {
        return view('contact-form::admin.contact-form.modals.entry-preview-modal');
    }

}
