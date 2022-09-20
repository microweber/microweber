<?php

namespace MicroweberPackages\Admin\Http\Livewire;


class AutoCompleteMultipleItemsComponent extends AutoCompleteComponent
{
    /**
     * Array of multiple selected items
     * @var array
     */
    public $selectedItems = [];

    /**
     * Default view of multiple selection autocomplete
     * @var string
     */
    public string $view = 'admin::livewire.auto-complete-multiple-items';

    /**
     * When we apply a multiple selections
     * @param $items
     * @return void
     */
    public function updatedSelectedItems($items)
    {
        $this->refreshQueryData();
        $this->emitSelf('$refresh');
        $this->emit('autoCompleteSelectItem', $this->selectedItemKey, $this->selectedItems);
        $this->closeDropdown();
    }

}
