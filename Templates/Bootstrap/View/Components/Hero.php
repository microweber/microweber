<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Hero extends Component
{

    public $class = '';
    public $image = '';

    /**
     * Create the component instance.
     */
    public function __construct($class='', $image='')
    {
        $this->class = $class;
        $this->image = $image;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.hero');
    }
}
