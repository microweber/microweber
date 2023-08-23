<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\CustomField\Models\CustomField;

class CustomFieldsListComponent extends AdminComponent
{
    public $relId;
    public $relType = 'content';

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
            $position = 0;
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
        return mw()->fields_manager->delete($id);
    }

    public function render()
    {
        $getCustomFields = CustomField::where('rel_type', $this->relType)
            ->where('rel_id', $this->relId)
            ->orderBy('position', 'asc')
            ->get();

        return view('custom_field::livewire.custom-fields-list-component',[
            'customFields' => $getCustomFields
        ]);
    }
}
