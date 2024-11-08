<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Input extends Component
{
    public $name;
    public $label;
    public $type;
    public $value;
    public $placeholder;
    public $required;
    public $disabled;
    public $help;
    public $errors;

    /**
     * Create the component instance.
     */
    public function __construct($name, $label = null, $type = 'text', $value = '', $placeholder = '', $required = false, $disabled = false, $help = '', $errors = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->help = $help;
        $this->errors = $errors;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.input');
    }
}
