<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Col extends Component
{
    public $size = 12;
    public $sizeLarge = 12;
    public $sizeSmall = 12;

    /**
     * Create the component instance.
     */
    public function __construct($size = 12, $sizeLarge = null, $sizeSmall = null)
    {
        if(!$sizeLarge) {
            $sizeLarge = $size;
        }
        if(!$sizeSmall) {
            $sizeSmall = $size;
        }

        $this->size = $size;
        $this->sizeLarge = $sizeLarge;
        $this->sizeSmall = $sizeSmall;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.col');
    }
}
