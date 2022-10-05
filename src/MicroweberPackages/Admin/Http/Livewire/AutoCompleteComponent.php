<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Livewire\Component;

class AutoCompleteComponent extends DropdownComponent
{
    /**
     * The eloquent laravel model
     * @var
     */
    public $model;

    /**
     * Selected item when we have single selection autocomplete
     * @var string
     */
    public $selectedItem = '';


    /**
     * @var string
     */
    public $selectedItemText = '';

    /**
     * Selected item key when we fire a event with key and value
     * @var string
     */
    public $selectedItemKey = 'auto_complete_id';

    /**
     * The model query
     * @var
     */
    public $query;

    /**
     * Sended data to view
     * @var array
     */
    public $data = [];


    /**
     * Default view of single selection autocomplete
     * @var string
     */
    public string $view = 'admin::livewire.auto-complete';


    /**
     * Placeholder text on ui
     * @var string
     */
    public string $placeholder = 'Type to search...';

    /**
     * Searching text on ui
     * @var string
     */
    public string $searchingText = 'Searching...';


    protected function getListeners()
    {
        return array_merge($this->listeners, [
            'autocompleteLoad'=>'load',
            'autocompleteRefresh'=>'$refresh',
            'autocompleteReset'=>'resetProperties'
        ]);
    }

    /**
     * @return void
     */
    public function mount()
    {
         if ($this->selectedItem) {
              $this->refreshQueryData();
         }
    }

    /**
     * @return void
     */
    public function updatedQuery()
    {
        $this->selectedItem = false;
        $this->refreshQueryData();
    }

    /**
     * Set your model query logic to search results
     * @return void
     */
    public function refreshQueryData()
    {
        $this->showDropdown($this->id);
    }

    /**
     * @return void
     */
    public function resetProperties()
    {
        $this->query = '';
        $this->selectItem(false);
        $this->closeDropdown();
    }

    /**
     * When we apply a one selection item
     * @param string $item
     * @return void
     */
    public function selectItem(string $item)
    {
        $json = @json_decode($item, true);
        if (!empty($json)) {
            $item = $json;
        }

        if (empty($item)) {
            $this->selectedItemText = '';
        }

        $this->selectedItem = $item;
        $this->refreshQueryData();
        $this->emitSelf('$refresh');

        $this->emit('autoCompleteSelectItem', $this->selectedItemKey, $this->selectedItem);
    }

    public function load($id)
    {
        if ($id == $this->id) {
            $this->showDropdown($id);
            $this->refreshQueryData();
        }
    }

    public function render()
    {
        return view($this->view);
    }
}
