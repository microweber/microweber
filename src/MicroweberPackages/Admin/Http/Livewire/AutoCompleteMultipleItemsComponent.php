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

    public string $placeholderWithTags = '';

    /**
     * When we apply a multiple selections
     * @param $items
     * @return void
     */
    public function updatedSelectedItems(array $items)
    {
        $this->selectedItems = $items;
        $this->refreshQueryData();
        $this->emitSelf('$refresh');
        $this->emit('autoCompleteSelectItem', $this->selectedItemKey, $this->selectedItems);
        $this->closeDropdown();
        $this->refreshPlaceholder();
    }

    /**
     * @return void
     */
    public function resetProperties()
    {
        $this->query = '';
        $this->data = false;
        $this->selectedItems = [];
        $this->updatedSelectedItems([]);
    }

    /**
     * @return void
     */
    public function mount()
    {
       $this->refreshPlaceholder();
    }

    public function refreshPlaceholder()
    {
        $this->placeholderWithTags = '';

        if (!empty($this->selectedItems)) {
            if (is_array($this->selectedItems)) {
                $items = array_map('ucfirst', $this->selectedItems);
                $this->placeholderWithTags = implode(', ', $items);
            }
        }
    }
}
