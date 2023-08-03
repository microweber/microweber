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
        'onReorderCustomFieldsList'=>'onReorderCustomFieldsList'
    ];

    public function onReorderCustomFieldsList($params)
    {
        if (isset($params['itemIds'])) {
            $itemIds = $params['itemIds'];
            $position = 1;
            foreach ($itemIds as $itemId) {
                $findCustomField = CustomField::where('id', $itemId)->first();
                if ($findCustomField) {
                    $findCustomField->position = $position;
                    $findCustomField->save();
                }
                $position++;
            }
        }
    }

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
