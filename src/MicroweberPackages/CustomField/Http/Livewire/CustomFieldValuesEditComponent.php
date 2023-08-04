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
        if (isset($params['itemIds'])) {
            $itemIds = $params['itemIds'];
            $position = 0;
            foreach ($itemIds as $itemId) {
                $findCustomField = CustomFieldValue::where('id', $itemId)->first();
                if ($findCustomField) {
                    $findCustomField->position = $position;
                    $findCustomField->save();
                }
                $position++;
            }
        }
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

    public function remove($id)
    {
//        if (count($this->inputs) == 1) {
//            $this->addError('inputs.'.$i, 'You must have at least one input.');
//            return;
//        }

      //  dd($id);

//        if (isset($this->inputs[$i])) {
//            $findCustomFieldValue = CustomFieldValue::where('custom_field_id', $this->customFieldId)
//                ->where('value', $this->inputs[$i])
//                ->first();
//            if ($findCustomFieldValue) {
//                $findCustomFieldValue->delete();
//            }
//
//            //unset($this->inputs[$i]);
//
//           // $this->emit('customFieldUpdated');
//        }
    }

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

            $values = array_values($this->inputs);

            $this->state['value'] = $values;

            mw()->fields_manager->save($this->state);

            $this->emit('customFieldUpdated');
        }
    }

    public function updatedState()
    {
        if (isset($this->state['value'])) {

            mw()->fields_manager->save($this->state);

            $this->emit('customFieldUpdated');
        }
    }

    public function render()
    {
        $this->customField = CustomField::where('id', $this->customFieldId)->first();
        $this->state['type'] = $this->customField->type;
        $this->state['rel_type'] = $this->customField->rel_type;
        $this->state['rel_id'] = $this->customField->rel_id;
        $this->state['id'] = $this->customField->id;

        return view('custom_field::livewire.custom-field-values-edit-component');
    }
}
