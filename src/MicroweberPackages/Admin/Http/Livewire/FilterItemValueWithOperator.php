<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemValueWithOperator extends DropdownComponent
{
    public $name = 'Value with operator';
    public string $view = 'admin::livewire.filters.filter-item-value-with-operator';

    public $itemOperatorValue;
    public $itemValue;

    public $itemValueKey;
    public $itemOperatorValueKey;

    public function resetProperties()
    {
        $this->itemValue = '';
        $this->itemOperatorValue = '';
        $this->closeDropdown();
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

    public function updatedItemOperatorValue()
    {
        $this->showDropdown($this->id);
        $this->emitEvents();
    }

    public function emitEvents()
    {
        $this->emit('autoCompleteSelectItem', $this->itemOperatorValueKey, $this->itemOperatorValue);
        $this->emit('autoCompleteSelectItem', $this->itemValueKey, $this->itemValue);
    }
}
