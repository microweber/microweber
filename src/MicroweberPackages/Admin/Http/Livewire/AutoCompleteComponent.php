<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Livewire\Component;

class AutoCompleteComponent extends Component
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
    public $selectedItem;

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
     * @var
     */
    public $data;

    /**
     * Show/Hide dropdown on view
     * @var bool
     */
    public $showDropdown = false;


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

    /**
     * @var string[]
     */
    public $listeners = [
      'autocompleteRefresh'=>'$refresh'
    ];

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
        //
    }

    /**
     * @return void
     */
    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    /**
     * @return void
     */
    public function showDropdown()
    {
        $this->showDropdown = true;
    }

    /**
     * @return void
     */
    public function resetProperties()
    {
        $this->query = '';
        $this->data = false;
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

        $this->selectedItem = $item;
        $this->refreshQueryData();
        $this->emitSelf('$refresh');

        $this->emit('autoCompleteSelectItem', $this->selectedItemKey, $this->selectedItem);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view($this->view);
    }
}
