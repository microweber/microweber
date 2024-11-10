<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Row extends Component
{
    public $class = '';
    public $flex = false;
    public $flexWrap = false;
    public $flexNoWrap = false;

    /**
     * Create the component instance.
     */
    public function __construct($class = '', $flex = false, $flexWrap = false, $flexNoWrap = false)
    {
        $this->class = $class;
        $this->flex = $flex;
        $this->flexWrap = $flexWrap;
        $this->flexNoWrap = $flexNoWrap;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.row');
    }
}
