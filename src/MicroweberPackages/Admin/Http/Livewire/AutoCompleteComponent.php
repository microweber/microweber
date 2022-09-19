<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Livewire\Component;

class AutoCompleteComponent extends Component
{
    public $model;
    public $view = 'admin::livewire.auto-complete';

    public function render()
    {
        return view($this->view);
    }
}
