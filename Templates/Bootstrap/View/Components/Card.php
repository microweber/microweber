<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Card extends Component
{
    public $theme;
    public $class;

    /**
     * Create the component instance.
     */
    public function __construct($theme = 'light',$class=''){
        $this->theme = $theme;
        $this->class = $class;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.card');
    }
}
