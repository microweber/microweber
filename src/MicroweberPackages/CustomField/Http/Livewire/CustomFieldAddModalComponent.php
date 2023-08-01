<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\CustomField\Models\CustomField;

class CustomFieldAddModalComponent extends AdminModalComponent
{
    public $contentId;
    public $contentType;

    public function add($type, $name)
    {
        $newCustomField = new CustomField();
        $newCustomField->name = $name;
        $newCustomField->rel_type = 'content';
        $newCustomField->rel_id = $this->contentId;
        $newCustomField->type = $type;
        $newCustomField->save();

        $this->closeModal();
        $this->emit('customFieldAdded');

        $this->emit('openModal','custom-field-edit-modal', [
            'customFieldId' => $newCustomField->id
        ]);
    }

    public function render()
    {
        return view('custom_field::livewire.custom-field-add-modal-component');
    }
}
