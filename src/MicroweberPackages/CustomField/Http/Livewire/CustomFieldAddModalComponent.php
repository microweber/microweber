<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\CustomField\CustomFieldsHelper;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

class CustomFieldAddModalComponent extends AdminModalComponent
{
    public $contentId;
    public $contentType;


    public function addExisting($customFieldId)
    {

        $findExisting = CustomField::where('id', $customFieldId)->first();

        $newCustomFieldId = mw()->fields_manager->save([
            'copy_of' => $findExisting->id,
            'rel_type' => $findExisting->rel_type,
            'rel_id' => $this->contentId,
            'type' => $findExisting->type,
        ]);

        $this->closeModal();
        $this->emit('customFieldAdded');

        $showEditModal = true;
        if ($findExisting->type == 'address') {
            $showEditModal = false;
        }

        if ($showEditModal) {
            $this->emit('openModal', 'custom-field-edit-modal', [
                'customFieldId' => $newCustomFieldId
            ]);
        }
    }

    public function add($type, $name)
    {
        $showEditModal = true;

        $newCustomField = new CustomField();
        $newCustomField->name = $name;
        $newCustomField->rel_type = 'content';
        $newCustomField->rel_id = $this->contentId;
        $newCustomField->type = $type;
        $newCustomField->save();

        if ($type == 'address') {
            $showEditModal = false;
            CustomFieldsHelper::generateFieldAddressValues('content', $this->contentId);
        }

        $values = CustomFieldsHelper::generateFieldNameValues($type);
        if (!empty($values)) {
            foreach ($values as $value) {
                $customFieldValue = new CustomFieldValue();
                $customFieldValue->custom_field_id = $newCustomField->id;
                $customFieldValue->value = $value;
                $customFieldValue->save();
            }
        }

        $this->closeModal();
        $this->emit('customFieldAdded');

        if ($showEditModal) {
            $this->emit('openModal', 'custom-field-edit-modal', [
                'customFieldId' => $newCustomField->id
            ]);
        }
    }

    public function render()
    {
        $existingFields = \MicroweberPackages\CustomField\Models\CustomField::where('rel_type', 'content')
            ->groupBy('name_key')
            ->orderBy('created_at','desc')
            ->get();

        return view('custom_field::livewire.custom-field-add-modal-component',[
            'existingFields' => $existingFields
        ]);
    }
}
