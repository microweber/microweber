<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire\Traits;

trait ShopCustomFieldsTrait {

    public $customFields = '';

    public function filterToggleCustomField($customFieldValueId)
    {
        if (!empty($this->customFields)) {
            $currentCustomFields = explode(',', $this->customFields);
        } else {
            $currentCustomFields = [];
        }

        if (in_array($customFieldValueId, $currentCustomFields)) {
            $currentCustomFields = array_diff($currentCustomFields, [$customFieldValueId]);
        } else {
            $currentCustomFields[] = $customFieldValueId;
        }

        $currentCustomFields = array_unique($currentCustomFields);
        $this->customFields = implode(',', $currentCustomFields);

        $this->setPage(1);
    }

    public function getCustomFields()
    {
        if (!empty($this->customFields)) {
            $currentCustomFields = explode(',', $this->customFields);
        } else {
            $currentCustomFields = [];
        }

        return $currentCustomFields;
    }

    public function filterClearCustomFields()
    {
        $this->customFields = '';

        $this->setPage(1);
    }

    public function filterRemoveCustomField($customFieldValueId)
    {
        $customFields = $this->getCustomFields();
        $customFields = array_diff($customFields, [$customFieldValueId]);
        $this->customFields = implode(',', $customFields);

        $this->setPage(1);
    }


}

