<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemValueWithOperator extends DropdownComponent
{
    public $name = 'Date';
    public string $view = 'admin::livewire.filters.filter-item-value-with-operator';

    public $itemOperatorValue;
    public $itemValue;

    public $itemValueKey;
    public $itemOperatorValueKey;

    public function load()
    {
        $this->showDropdown($this->id);
    }

    public function emitEvents()
    {
        $this->emit('autoCompleteSelectItem', $this->itemOperatorValueKey, $this->itemOperatorValue);
        $this->emit('autoCompleteSelectItem', $this->itemValueKey, $this->itemValue);
    }

   public function updatedItemValue()
    {
        $this->emitEvents();
    }

    public function updatedItemOperatorValue()
    {
        $this->emitEvents();
    }

/*    protected function getListeners()
    {
        return array_merge($this->listeners, [

        ]);
    }*/

}
