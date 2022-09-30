<?php

namespace MicroweberPackages\Admin\Http\Livewire;


class AutoCompleteMultipleSelectComponent extends AutoCompleteComponent
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
     * @var string
     */
    public string $placeholderWithTags = '';

    /**
     * @var string[]
     */
    public $listeners = [
        'closeDropdown'=>'closeDropdown',
        'autocompleteRefresh'=>'$refresh',
        'resetProperties'=>'resetProperties'
    ];

    public $closeDropdownAfterSelect = false;

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

        if ($this->closeDropdownAfterSelect) {
            $this->closeDropdown();
        }

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
