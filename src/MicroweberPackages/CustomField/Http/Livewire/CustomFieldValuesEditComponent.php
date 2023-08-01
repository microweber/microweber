<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\CustomField\Models\CustomField;

class CustomFieldValuesEditComponent extends AdminComponent
{
    public $customFieldId;
    public $state = [];
    public $customFieldValues = [];

    public $inputs = [];

    public $i = 1;


    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }


    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public function mount($customFieldId)
    {
        $this->customFieldId = $customFieldId;
    }

    public function updatedState()
    {
        if (isset($this->state['value'])) {

            $getCustomField = CustomField::where('id', $this->customFieldId)->first();
            $getCustomField->value = $this->state['value'];
            $getCustomField->save();

            $this->emit('customFieldUpdated');
        }
    }

    public function render()
    {
        $getCustomField = CustomField::where('id', $this->customFieldId)->first();
        // $this->customFieldValues = $getCustomField->fieldValue;

        return view('custom_field::livewire.custom-field-values-edit-component',[
            'customField' => $getCustomField,
        ]);
    }
}
