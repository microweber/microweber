<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemValueWithOperator extends DropdownComponent
{
    public $name = 'Value with operator';
    public string $view = 'admin::livewire.filters.filter-item-value-with-operator';

    public $itemOperatorValue = 'greater';
    public $itemValue;

    public $itemValueKey;
    public $itemOperatorValueKey;

    public function resetProperties()
    {
        $this->itemValue = '';
        $this->itemOperatorValue = '';
        $this->closeDropdown();
        $this->dispatchEvents();
    }

    public function hideFilterItem($id)
    {
        if ($this->id == $id) {
            $this->dispatch('hideFilterItem', $this->itemValueKey);
            $this->resetProperties();
        }
    }

    public function updatedItemValue()
    {
        $this->showDropdown($this->id);
        $this->dispatchEvents();
    }

    public function updatedItemOperatorValue()
    {
        $this->showDropdown($this->id);
        $this->dispatchEvents();
    }

    public function emitEvents()
    {
        $this->dispatch('autoCompleteSelectItem', $this->itemOperatorValueKey, $this->itemOperatorValue);
        $this->dispatch('autoCompleteSelectItem', $this->itemValueKey, $this->itemValue);
    }
}
