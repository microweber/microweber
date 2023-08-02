<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

class CustomFieldValuesEditComponent extends AdminComponent
{
    public $customFieldId;
    public $customField;
    public $state = [];
    public $inputs = [];

    public $listeners = [
        'customFieldUpdated' => '$refresh'
    ];

    public function add()
    {
        $this->inputs[] = 'Your text here';

        $newCustomFieldValue = new CustomFieldValue();
        $newCustomFieldValue->custom_field_id = $this->customFieldId;
        $newCustomFieldValue->value = 'Your text here';
        $newCustomFieldValue->save();

    }


    public function remove($i)
    {
        if (count($this->inputs) == 1) {
            $this->addError('inputs.'.$i, 'You must have at least one input.');
            return;
        }

        $findCustomFieldValue = CustomFieldValue::where('custom_field_id', $this->customFieldId)
                                    ->where('value', $this->inputs[$i])
                                    ->first();
        $findCustomFieldValue->delete();

        unset($this->inputs[$i]);

        $this->emit('customFieldUpdated');
    }

    public function mount($customFieldId)
    {
        $this->customFieldId = $customFieldId;

        $getCustomField = CustomField::where('id', $this->customFieldId)->first();
        foreach($getCustomField->fieldValue as $fieldValue) {
              $this->inputs[] = $fieldValue->value;
        }

        if($getCustomField->type == 'checkbox' || $getCustomField->type == 'dropdown' || $getCustomField->type == 'radio') {
            if (empty($this->inputs)) {
                $this->add();
                $this->add();
                $this->add();
            }
        }

        $this->customField = $getCustomField;

    }

    public function updatedInputs()
    {
        if (!empty($this->inputs)) {
              $getCustomField = CustomField::where('id', $this->customFieldId)->first();
              $getCustomField->fieldValue()->delete();

                foreach($this->inputs as $input) {
                    $getCustomField->fieldValue()->create([
                        'value' => $input
                    ]);
                }

                $this->emit('customFieldUpdated');
        }
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
        $this->customField = CustomField::where('id', $this->customFieldId)->first();
        if ($this->customField->fieldValue->count() == 0) {
            $this->add();
            $this->add();
            $this->add();
        }

        return view('custom_field::livewire.custom-field-values-edit-component');
    }
}
