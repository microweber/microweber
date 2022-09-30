<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Livewire\Component;

class DropdownComponent extends Component
{

    /**
     * Show/Hide dropdown on view
     * @var bool
     */
    public $showDropdown = false;


    /**
     * Default view of dropdown
     * @var string
     */
    public string $view = 'admin::livewire.dropdown';

    public $listeners = [
        'closeDropdown'=>'closeDropdown'
    ];

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

    public function render()
    {
        return view($this->view);
    }
}
