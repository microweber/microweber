<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Checkbox extends Component
{
    public $name;
    public $label;
    public $checked;
    public $disabled;
    public $errors;
    public $value;

    /**
     * Create the component instance.
     */
    public function __construct($name, $label = null, $checked = false, $disabled = false, $errors = null, $value = 1)
    {
        $this->name = $name;
        $this->label = $label;
        $this->checked = $checked;
        $this->disabled = $disabled;
        $this->errors = $errors;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.checkbox');
    }
}
