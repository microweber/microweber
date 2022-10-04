<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Livewire\Component;

class DropdownComponent extends Component
{

    /**
     * @var string
     */
    public $name = 'MyDropdown';

    /**
     * Show/Hide dropdown on view
     * @var int
     */
    public $showDropdown = 0;


    /**
     * Default view of dropdown
     * @var string
     */
    public string $view = 'admin::livewire.dropdown';

    public $listeners = [
        'showDropdown'=>'showDropdown',
        'closeDropdown'=>'closeDropdown',
    ];

    /**
     * @return void
     */
    public function closeDropdown($wireElementId = false)
    {
        if ($wireElementId == $this->id) {
            $this->showDropdown = 0;
        }
    }

    /**
     * @return void
     */
    public function showDropdown($wireElementId = false)
    {
        if ($wireElementId == $this->id) {
            $this->showDropdown = 1;
        }
    }

    public function render()
    {
        return view($this->view);
    }
}
