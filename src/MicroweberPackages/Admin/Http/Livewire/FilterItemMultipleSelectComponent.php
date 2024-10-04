<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemMultipleSelectComponent extends AutoCompleteMultipleSelectComponent
{
    public $name = 'Component';
    public $perPage = 10;
    public $total = 0;

    public string $view = 'admin::livewire.filters.filter-item-mulitple-select';

    public function hideFilterItem($id)
    {
        if ($this->getId() == $id) {
            $this->closeDropdown($id);
            $this->dispatch('hideFilterItem', $this->selectedItemKey);
            $this->resetProperties();
        }
    }

}
