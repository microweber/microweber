<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class NavItem extends Component
{
    public $href;
    public $active;

    /**
     * Create the component instance.
     */
    public function __construct($href = '#', $active = false)
    {
        $this->href = $href;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.navitem');
    }
}
