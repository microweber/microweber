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

    public function onReorderCustomFieldValuesList($params)
    {

    }

    public function add()
    {
        $values = CustomFieldsHelper::generateFieldNameValues($this->customField->type);
        $title = $values[0];

        $this->inputs[] = $title;

        $newCustomFieldValue = new CustomFieldValue();
        $newCustomFieldValue->custom_field_id = $this->customFieldId;
        $newCustomFieldValue->value = $title;
        $newCustomFieldValue->save();

    }

    public function remove($i)
    {
        if (count($this->inputs) == 1) {
            $this->addError('inputs.'.$i, 'You must have at least one input.');
            return;
        }

        if (isset($this->inputs[$i])) {
            $findCustomFieldValue = CustomFieldValue::where('custom_field_id', $this->customFieldId)
                ->where('value', $this->inputs[$i])
                ->first();
            if ($findCustomFieldValue) {
                $findCustomFieldValue->delete();
            }

            unset($this->inputs[$i]);

            $this->emit('customFieldUpdated');
        }
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
                $values = CustomFieldsHelper::generateFieldNameValues($getCustomField->type);
                if (!empty($values)) {
                    foreach ($values as $value) {
                        $customFieldValue = new CustomFieldValue();
                        $customFieldValue->custom_field_id = $getCustomField->id;
                        $customFieldValue->value = $value;
                        $customFieldValue->save();
                    }
                }
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
            $values = CustomFieldsHelper::generateFieldNameValues($this->customField->type);
            if (!empty($values)) {
                foreach ($values as $value) {
                    $customFieldValue = new CustomFieldValue();
                    $customFieldValue->custom_field_id = $this->customField->id;
                    $customFieldValue->value = $value;
                    $customFieldValue->save();
                }
            }
        }

        return view('custom_field::livewire.custom-field-values-edit-component');
    }
}
