<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\CustomField\Models\CustomField;

class CustomFieldEditModalComponent extends AdminModalComponent
{
    public $customFieldId;
    public $state = [];

    public function mount($customFieldId)
    {
        $this->customFieldId = $customFieldId;
    }

    public function updatedState()
    {
        $getCustomField = CustomField::where('id', $this->customFieldId)->first();
        if ($getCustomField) {

            $getCustomField->name = $this->state['name'];
            $getCustomField->options = $this->state['options'];
            $getCustomField->type = $this->state['type'];
            $getCustomField->required = $this->state['required'];
            $getCustomField->error_text = $this->state['error_text'];
            $getCustomField->show_label = $this->state['show_label'];
            $getCustomField->placeholder = $this->state['placeholder'];

            if (isset($this->state['value'])) {
                $getCustomField->value = $this->state['value'];
            }

            $getCustomField->save();

            $this->emit('customFieldUpdated');
        }
    }

    public function render()
    {
        $getCustomField = CustomField::where('id', $this->customFieldId)->first();
        $this->state = $getCustomField->toArray();

        $this->state['value'] = $getCustomField->fieldValue;

        return view('custom_field::livewire.custom-field-edit-modal-component',[
            'customField' => $getCustomField
        ]);
    }
}
