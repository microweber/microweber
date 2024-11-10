<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Container extends Component
{
    public $fluid = false;
    public $class ='';

    /**
     * Create the component instance.
     */
    public function __construct($fluid = false,$class='') {
        $this->fluid = $fluid;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {

        return view('modules.components::components.container');
    }
}
