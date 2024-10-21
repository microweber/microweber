<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Col extends Component
{
    public $size = 6;

    /**
     * Create the component instance.
     */
    public function __construct($size = 6) {
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.col');
    }
}
