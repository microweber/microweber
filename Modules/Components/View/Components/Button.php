<?php

namespace Modules\Components\View\Components;

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
    public $url;
    public $submit;

    /**
     * Create the component instance.
     */
    public function __construct($type = 'primary', $size = 'md', $outline = false, $disabled = false, $block = false, $class = '', $url = null, $submit = false)
    {
        $this->type = $type;
        $this->size = $size;
        $this->outline = $outline;
        $this->disabled = $disabled;
        $this->block = $block;
        $this->class = $class;
        $this->url = $url;
        $this->submit = $submit;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.button');
    }
}
