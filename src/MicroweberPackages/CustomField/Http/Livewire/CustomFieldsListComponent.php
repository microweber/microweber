<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\CustomField\Models\CustomField;

class CustomFieldsListComponent extends AdminComponent
{
    public $contentId;
    public $contentType;

    public $listeners = [
        'customFieldUpdated'=>'$refresh',
        'customFieldAdded'=>'$refresh',
        'executeCustomFieldDelete' => 'executeCustomFieldDelete',
    ];

    public function executeCustomFieldDelete($id) {
        $findCustomField = CustomField::where('id', $id)->first();
        if ($findCustomField) {
            $findCustomField->delete();
        }
    }

    public function render()
    {
        $getCustomFields = CustomField::where('rel_type', 'content')
            ->where('rel_id', $this->contentId)
            ->orderBy('position', 'asc')
            ->get();

        return view('custom_field::livewire.custom-fields-list-component',[
            'customFields' => $getCustomFields
        ]);
    }
}
