<?php

namespace MicroweberPackages\CustomField\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use Modules\CustomFields\Models\CustomField;

class CustomFieldsListComponent extends AdminComponent
{
    public $relId = 0;
    public $relType = 'content';

    public $listeners = [
        'customFieldListRefresh' => '$refresh',
        'executeCustomFieldDelete' => 'executeCustomFieldDelete',
        'onReorderCustomFieldsList' => 'onReorderCustomFieldsList'
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

        $this->dispatch('customFieldUpdated');
    }

    public function executeCustomFieldDelete($id)
    {
        mw()->fields_manager->delete($id);
    }

    public function render()
    {
        $getCustomFields = CustomField::where('rel_type', $this->relType)
            ->where('rel_id', $this->relId);

        if ($this->relId == 0) {
            $getCustomFields = $getCustomFields->where('session_id', mw()->user_manager->session_id());
        }

        $getCustomFields = $getCustomFields->orderBy('position', 'asc')
            ->get();

        return view('custom_field::livewire.custom-fields-list-component', [
            'customFields' => $getCustomFields
        ]);
    }
}
