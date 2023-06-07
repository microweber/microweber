<?php

namespace MicroweberPackages\Modules\ContactForm\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Form\Models\FormData;

class FormDataPreviewModalComponent extends AdminModalComponent
{
    public $formDataId;
    public $formData;
    public $confirmingDeleteId;

    public function mount($formDataId = null)
    {
        $this->formDataId = $formDataId;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete($id)
    {
        $formData = FormData::where('id', $id)->first();
        if ($formData == null) {
            return [];
        }

        $formData->delete();
        $this->emit('loadList');
        $this->closeModal();
    }

    public function render()
    {
        $getFormData = FormData::where('id', $this->formDataId)->first();
        if ($getFormData) {
            $this->formData = $getFormData;
        }

        return view('contact-form::admin.contact-form.modals.form-data-preview-modal');
    }

}
