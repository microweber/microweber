<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;

class Section extends Component
{
     public $class;

    public function __construct($class = null)
    {
        $this->class = $class;
    }

    public function render()
    {
        return view('modules.components::components.section');
    }
}
