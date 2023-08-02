<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

class CustomFieldAddModalComponent extends AdminModalComponent
{
    public $contentId;
    public $contentType;

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
            $this->generateFieldAddressValues('content', $this->contentId);
        }

        $values = $this->generateFieldNameValues($type);
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

    private function generateFieldAddressValues($relType, $relId)
    {
        $fields = 'Country[type=country,field_size=12,show_placeholder=true],';
        $fields .= 'City[type=text,field_size=4,show_placeholder=true],';
        $fields .= 'State/Province[type=text,field_size=4,show_placeholder=true],';
        $fields .= 'Zip/Post code[type=text,field_size=4,show_placeholder=true],';
        $fields .= 'Address[type=textarea,field_size=12,show_placeholder=true]';

        return mw()->fields_manager->makeDefault($relType, $relId, $fields);
    }

    private function generateFieldNameValues($type)
    {
        $values = [];

        if ($type == 'radio') {
            $typeText = _e('Option', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        if ($type == 'checkbox') {
            $typeText = _e('Check', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        if ($type == 'dropdown') {
            $typeText = _e('Select', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        return $values;
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
