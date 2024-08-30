<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire\Traits;

trait ShopCustomFieldsTrait {

    public $customFields = [];

    public function filterToggleCustomField($nameKey, $customFieldValue)
    {
        $findCustomField = false;
        if (isset($this->customFields[$nameKey])) {
            if (in_array($customFieldValue, $this->customFields[$nameKey])) {
                $findCustomField = true;
                foreach ($this->customFields[$nameKey] as $key => $value) {
                    if ($value == $customFieldValue) {
                        unset($this->customFields[$nameKey][$key]);
                    }
                }
            }
        }

        if (!$findCustomField) {
            $this->customFields[$nameKey][] = $customFieldValue;
        }

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

