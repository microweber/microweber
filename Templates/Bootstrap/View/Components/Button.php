<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Button extends Component
{
    public $type;
    public $size;
    public $outline;
    public $disabled;
    public $block;
    public $class;

    /**
     * Create the component instance.
     */
    public function __construct($type = 'primary', $size = 'md', $outline = false, $disabled = false, $block = false, $class = '')
    {
        $this->type = $type;
        $this->size = $size;
        $this->outline = $outline;
        $this->disabled = $disabled;
        $this->block = $block;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.button');
    }
}
