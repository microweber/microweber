<?php

namespace Templates\Bootstrap\View\Components;

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
        return view('templates.bootstrap::components.section');
    }
}
