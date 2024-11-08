<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SimpleText extends Component
{

    public $align;
    public $class = '';
    /**
     * Create the component instance.
     */
    public function __construct($align = null,$class = '') {
        $this->align = $align;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.simple-text');
    }
}
