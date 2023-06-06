<?php

namespace MicroweberPackages\Modules\ContactForm\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Form\Models\FormData;

class FormDataPreviewModalComponent extends AdminModalComponent
{
    public $formData;

    public function mount($formDataId = null)
    {
        $getFormData = FormData::where('id', $formDataId)->first();
        if ($getFormData) {
            $this->formData = $getFormData->toArray();
        }
    }

    public function render()
    {
        return view('contact-form::admin.contact-form.modals.form-data-preview-modal');
    }

}
