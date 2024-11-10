<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Navbar extends Component
{
    public $brand;
    public $brandUrl;
    public $expand;
    public $dark;
    public $fixed;

    /**
     * Create the component instance.
     */
    public function __construct($brand = '', $brandUrl = '#', $expand = 'md', $dark = false, $fixed = '')
    {
        $this->brand = $brand;
        $this->brandUrl = $brandUrl;
        $this->expand = $expand;
        $this->dark = $dark;
        $this->fixed = $fixed;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.navbar');
    }
}
