<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemValue extends DropdownComponent
{
    public $name = 'Value with range';
    public string $view = 'admin::livewire.filters.filter-item-value';

    public $itemValue;
    public $itemValueKey;

    public function load()
    {
        $this->showDropdown($this->id);
    }

    public function emitEvents()
    {
        $this->emit('autoCompleteSelectItem', $this->itemValueKey, $this->itemValue);
    }

   public function updatedItemValue()
    {
        $this->emitEvents();
    }

/*    protected function getListeners()
    {
        return array_merge($this->listeners, [

        ]);
    }*/

}
