<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\CustomField\CustomFieldsHelper;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

class CustomFieldValuesEditComponent extends AdminComponent
{
    public $customFieldId;
    public $customField;
    public $state = [];
    public $inputs = [];

    public $listeners = [
        'customFieldUpdated' => '$refresh',
        'onReorderCustomFieldValuesList' => 'onReorderCustomFieldValuesList'
    ];






    public function mount($customFieldId)
    {
        $this->customFieldId = $customFieldId;

        $this->customField = CustomField::where('id', $this->customFieldId)->first();
        foreach($this->customField->fieldValue as $fieldValue) {
          $this->inputs[$fieldValue->id] = $fieldValue->value;
        }

    }

    public function updatedInputs()
    {
        if (!empty($this->inputs)) {

            $saveField = $this->state;
           // $saveField['value'] = array_values($this->inputs);

            mw()->fields_manager->save($saveField);

            $this->emit('customFieldUpdated');
        }
    }

    public function updatedState()
    {
        mw()->fields_manager->save($this->state);

        $this->emit('customFieldUpdated');
    }

    public function render()
    {
        $this->customField = CustomField::where('id', $this->customFieldId)->first();
        $this->state['type'] = $this->customField->type;
        $this->state['rel_type'] = $this->customField->rel_type;
        $this->state['rel_id'] = $this->customField->rel_id;
        $this->state['id'] = $this->customField->id;
        $this->state['options'] = $this->customField->options;

        if ($this->customField->type == 'checkbox' || $this->customField->type == 'dropdown' || $this->customField->type == 'radio') {
            // multiple values
        } else {
            // One value
            if ($this->customField->fieldValue->count() > 0) {
                $this->state['value'] = $this->customField->fieldValue[0]->value;
            }
        }

        return view('custom_field::livewire.custom-field-values-edit-component');
    }
}
