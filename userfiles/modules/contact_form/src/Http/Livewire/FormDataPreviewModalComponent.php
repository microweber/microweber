<?php

namespace MicroweberPackages\Modules\ContactForm\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Form\Models\FormData;

class FormDataPreviewModalComponent extends AdminModalComponent
{
    public $formData;
    public $confirmingDeleteId;

    public function mount($formDataId = null)
    {
        $getFormData = FormData::where('id', $formDataId)->first();
        if ($getFormData) {
            if ($getFormData->is_read != 1) {
                $getFormData->is_read = 1;
                $getFormData->save();
                $this->emit('loadList');
            }

            $this->formData = $getFormData;
        }
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
        return view('contact-form::admin.contact-form.modals.form-data-preview-modal');
    }

}
