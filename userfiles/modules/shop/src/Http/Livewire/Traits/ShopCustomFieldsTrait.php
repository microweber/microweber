<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire\Traits;

trait ShopCustomFieldsTrait {

    public $customFields = [];

    public function filterToggleCustomField($nameKey, $customFieldValueId)
    {
        $this->customFields[$nameKey] = $customFieldValueId;
        $this->setPage(1);
    }

    public function getCustomFields()
    {
        return $this->customFields;
    }

    public function filterClearCustomFields()
    {
        $this->customFields = [];
        $this->setPage(1);
    }

    public function filterRemoveCustomField()
    {

        $this->setPage(1);
    }

}

