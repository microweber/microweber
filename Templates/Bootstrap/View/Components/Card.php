<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Card extends Component
{
    public $theme;

    /**
     * Create the component instance.
     */
    public function __construct($theme = 'light') {
        $this->theme = $theme;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.card');
    }
}
