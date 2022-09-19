<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Livewire\Component;

class AutoCompleteComponent extends Component
{
    public $model;
    public $selectedItem;
    public $query;
    public $data;
    public $showDropdown = false;

    // Ui settings
    public string $view = 'admin::livewire.auto-complete';
    public string $placeholder = 'Type to search...';
    public string $searchingText = 'Searching...';

    public function mount()
    {
        /*  if (isset($this->filters['customerId'])) {
              $this->selectedItem = $this->filters['customerId'];
              $this->refreshQueryData();
          }*/
    }

    public function updatedQuery()
    {
        $this->selectedItem = false;
        $this->refreshQueryData();
    }

    public function refreshQueryData()
    {
        //
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function showDropdown()
    {
        $this->showDropdown = true;
    }

    public function resetProperties()
    {
        $this->query = '';
        $this->data = false;
    }

    public function selectItem(int $id)
    {
        $this->selectedItem = $id;
        $this->refreshQueryData();
        $this->emitSelf('$refresh');
    }

    public function render()
    {
        return view($this->view);
    }
}
