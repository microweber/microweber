<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\CustomField\Models\CustomField;

class CustomFieldValuesEditComponent extends AdminComponent
{
    public $customFieldId;
    public $customField;
    public $state = [];
    public $customFieldValues = [];

    public $inputs = [];

    public function add()
    {
        $this->inputs[] = 'Your text here';
    }


    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public function mount($customFieldId)
    {
        $this->customFieldId = $customFieldId;

        $getCustomField = CustomField::where('id', $this->customFieldId)->first();
        foreach($getCustomField->fieldValue as $fieldValue) {
              $this->inputs[] = $fieldValue->value;
        }
        $this->customField = $getCustomField;

    }

  /*  public function updatedState()
    {
        if (isset($this->state['value'])) {

            $getCustomField = CustomField::where('id', $this->customFieldId)->first();
            $getCustomField->value = $this->state['value'];
            $getCustomField->save();

            $this->emit('customFieldUpdated');
        }
    }*/

    public function render()
    {
        return view('custom_field::livewire.custom-field-values-edit-component');
    }
}
