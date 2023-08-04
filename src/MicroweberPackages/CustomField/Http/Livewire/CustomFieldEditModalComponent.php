<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

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
        $save = mw()->fields_manager->save($this->state);

        $this->showSettings($this->state['type']);
        $this->emit('customFieldUpdated');
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
        $this->showSettings($getCustomField->type);

        return view('custom_field::livewire.custom-field-edit-modal-component',[
            'customField' => $getCustomField
        ]);
    }
}
