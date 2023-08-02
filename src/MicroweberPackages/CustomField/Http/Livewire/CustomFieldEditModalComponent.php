<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\CustomField\Models\CustomField;

class CustomFieldEditModalComponent extends AdminModalComponent
{
    public $customFieldId;
    public $state = [];

    public $showValueSettings = false;
    public $showRequiredSettings = false;
    public $showLabelSettings = false;
    public $showPlaceholderSettings = false;
    public $showErrorTextSettings = false;
    public $showOptionsSettings = false;


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

            $this->showSettings($getCustomField->type);

            $this->emit('customFieldUpdated');
        }
    }

    public function showSettings($type)
    {
        $this->showValueSettings = false;
        $this->showRequiredSettings = false;
        $this->showLabelSettings = false;
        $this->showPlaceholderSettings = false;
        $this->showOptionsSettings = false;

        if ($type == 'text'
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
            $this->showPlaceholderSettings = true;
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

    public function render()
    {
        $getCustomField = CustomField::where('id', $this->customFieldId)->first();
        $this->state = $getCustomField->toArray();
        $this->state['value'] = $getCustomField->fieldValue;

        $this->showSettings($getCustomField->type);

        return view('custom_field::livewire.custom-field-edit-modal-component',[
            'customField' => $getCustomField
        ]);
    }
}
