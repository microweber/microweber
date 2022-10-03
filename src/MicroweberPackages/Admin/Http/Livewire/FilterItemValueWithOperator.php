<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemValueWithOperator extends DropdownComponent
{
    public $name = 'Date';
    public string $view = 'admin::livewire.filters.filter-item-value-with-operator';

    public $itemOperatorValue = 0;
    public $itemValue = 0;

    public function load()
    {
        $this->showDropdown($this->id);
    }

/*    protected function getListeners()
    {
        return array_merge($this->listeners, [

        ]);
    }*/

}
