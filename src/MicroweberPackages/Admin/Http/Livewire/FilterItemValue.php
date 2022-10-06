<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemValue extends DropdownComponent
{
    public $name = 'Value with range';
    public string $view = 'admin::livewire.filters.filter-item-value';

    public $itemValue;
    public $itemValueKey;

    public function resetProperties()
    {
        $this->itemValue = '';
        $this->closeDropdown($this->id);
        $this->emitEvents();
    }

    public function hideFilterItem($id)
    {
        if ($this->id == $id) {
            $this->emit('hideFilterItem', $this->itemValueKey);
            $this->resetProperties();
        }
    }

    public function updatedItemValue()
    {
        $this->showDropdown($this->id);
        $this->emitEvents();
    }

    public function emitEvents()
    {
        $this->emit('autoCompleteSelectItem', $this->itemValueKey, $this->itemValue);
    }
}
