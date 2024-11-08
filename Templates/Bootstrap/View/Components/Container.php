<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Container extends Component
{
    public $fluid;

    /**
     * Create the component instance.
     */
    public function __construct($fluid = false) {
        $this->fluid = $fluid;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.container');
    }
}
