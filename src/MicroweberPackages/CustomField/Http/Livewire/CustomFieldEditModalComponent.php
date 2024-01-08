<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Admin\Http\Livewire\AdminMwTopDialogIframeComponent;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

class CustomFieldEditModalComponent extends AdminMwTopDialogIframeComponent
{

    public $modalSettings = [
        'overlay' => true,
        'overlayClose' => false,
    ];

    public $customFieldId = false;
    public $state = [];
    public $inputs = [];
    public $priceModifiers = [];

    public $showValueSettings = false;
    public $showRequiredSettings = false;
    public $showLabelSettings = false;
    public $showPlaceholderSettings = false;
    public $showErrorTextSettings = false;
    public $showOptionsSettings = false;

    public $listeners = [
        'customFieldUpdated' => '$refresh',
        'onReorderCustomFieldValuesList' => 'onReorderCustomFieldValuesList'
    ];

    public function mount($customFieldId = false)
    {
        $this->customFieldId = $customFieldId;
    }

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
        $typeText = _e('New Value', true);
        if ($this->state['type'] == 'radio') {
            $typeText = _e('New Option', true);
        }

        if ($this->state['type'] == 'checkbox') {
            $typeText = _e('New Check', true);
        }

        if ($this->state['type'] == 'dropdown') {
            $typeText = _e('New Select', true);
        }

        $newCustomFieldValue = new CustomFieldValue();
        $newCustomFieldValue->custom_field_id = $this->customFieldId;
        $newCustomFieldValue->value = $typeText;
        $newCustomFieldValue->save();

        $this->inputs[$newCustomFieldValue->id] = $typeText;
        $this->priceModifiers[$newCustomFieldValue->id] = 0;
    }

    public function remove($id)
    {
        if (count($this->inputs) == 1) {
            $this->addError('inputs.' . $id, 'You must have at least one input.');
            return;
        }

        if (isset($this->inputs[$id])) {

            $findCustomFieldValue = CustomFieldValue::where('custom_field_id', $this->customFieldId)
                ->where('id', $id)
                ->first();
            if ($findCustomFieldValue) {
                $findCustomFieldValue->delete();
            }
            $this->priceModifiers[$id] =  false;

            unset($this->priceModifiers[$id]);
            unset($this->inputs[$id]);
            $this->dispatchGlobalBrowserEvent('customFieldUpdated');
        }
    }

    public function updatedState()
    {
        $this->save();
    }
    public function updatedPriceModifiers()
    {
        if (!empty($this->priceModifiers)) {
            foreach ($this->priceModifiers as $custFieldValueId => $priceModifierValue) {
                $valueItem = CustomFieldValue::where('id', $custFieldValueId)->first();
                if($valueItem){
                    $valueItem->price_modifier = $priceModifierValue;
                    $valueItem->save();
                }
            }
        }

    }
    public function updatedInputs()
    {
        if (!empty($this->inputs)) {


            $this->state['value'] = array_values($this->inputs);

            mw()->fields_manager->save($this->state);

            $this->refreshState();

        }
    }

    public function save()
    {

        $custFieldId = mw()->fields_manager->save($this->state);

        if ($this->priceModifiers and !empty($this->priceModifiers)) {

//            foreach ($this->priceModifiers as $custFieldValueId => $priceModifierValue) {
//             //   $valueItem = CustomFieldValue::where('id', $custFieldValueId)->firstOrNew();
//
//
//
//                $valueItem = DB::table('custom_fields_values')
//                    ->where('id', $custFieldValueId)
//                    ->update(['price_modifier' => $priceModifierValue]);
////                if ($valueItem) {
////                    $valueItem->price_modifier = $priceModifierValue;
////                    $valueItem->save();
////                }
//            }

        }

        $this->showSettings($this->state['type']);
        $this->dispatchGlobalBrowserEvent('customFieldUpdated');
    }

    public function showSettings($type)
    {
        $this->showValueSettings = false;
        $this->showRequiredSettings = false;
        $this->showLabelSettings = false;
        $this->showPlaceholderSettings = false;
        $this->showOptionsSettings = false;
        $this->showErrorTextSettings = false;

        if ($type == 'text'
            || $type == 'date'
            || $type == 'time'
            || $type == 'number'
            || $type == 'phone'
            || $type == 'website'
            || $type == 'email'
            || $type == 'address'
            || $type == 'country'
            || $type == 'color') {
            $this->showValueSettings = true;
            $this->showRequiredSettings = true;
            $this->showLabelSettings = true;
            $this->showPlaceholderSettings = true;
        }

        if ($type == 'upload') {
            $this->showRequiredSettings = true;
            $this->showLabelSettings = true;
            $this->showPlaceholderSettings = true;
        }

        if ($type == 'radio' || $type == 'dropdown' || $type == 'checkbox') {
            $this->showValueSettings = true;
            $this->showRequiredSettings = true;
            $this->showLabelSettings = true;
            $this->showPlaceholderSettings = false;
            $this->showErrorTextSettings = true;
        }

        if ($type == 'price') {
            $this->showValueSettings = true;
        }

        if ($type == 'hidden') {
            $this->showValueSettings = true;
        }

        if ($type == 'property') {
            $this->showValueSettings = true;
        }
    }

    public function refreshState()
    {
        $this->customField = CustomField::where('id', $this->customFieldId)->first();
        if ($this->customField) {
            $this->state = $this->customField->toArray();
        }
        if ($this->state and $this->state['type'] == 'upload') {
            if (!isset($this->state['options']['file_types'])) {
                $this->state['options']['file_types'] = [];
            }
            if (!is_array($this->state['options']['file_types'])) {
                $this->state['options']['file_types'] = [];
            }
        }

        if ($this->customField and $this->customField->type == 'checkbox' || $this->customField->type == 'dropdown' || $this->customField->type == 'radio') {
            // multiple values
            if ($this->customField->fieldValue->count() > 0) {
                foreach ($this->customField->fieldValue as $fieldValue) {
                    $this->inputs[$fieldValue->id] = $fieldValue->value;
                    $this->priceModifiers[$fieldValue->id] = $fieldValue->price_modifier;
                }
            }
        } else {
            // One value
            if ($this->customField and $this->customField->fieldValue->count() > 0) {
                $this->state['value'] = $this->customField->fieldValue[0]->value;
            }
        }
        if ($this->customField) {
            $this->showSettings($this->customField->type);
        }

    }

    public function render()
    {
        $this->refreshState();

        return view('custom_field::livewire.custom-field-edit-modal-component');
    }
}
