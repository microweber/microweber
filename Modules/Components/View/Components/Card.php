<?php

namespace Modules\Components\View\Components;

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
        return view('modules.components::components.card');
    }
}
