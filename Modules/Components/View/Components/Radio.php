<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Radio extends Component
{
    public $name;
    public $label;
    public $checked;
    public $disabled;
    public $errors;
    public $value;
    public $id;

    /**
     * Create the component instance.
     */
    public function __construct($name, $label = null, $checked = false, $disabled = false, $errors = null, $value = 1, $id = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->checked = $checked;
        $this->disabled = $disabled;
        $this->errors = $errors;
        $this->value = $value;
        $this->id = $id ?? str_slug($label); // Default to slugged label if id is not provided
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.radio');
    }
}
