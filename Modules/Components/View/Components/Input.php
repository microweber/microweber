<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Input extends Component
{
    /**
     * Create the component instance.
     *
     * @param string $name Input name
     * @param string|null $label Input label
     * @param string $type Input type (text, email, password, etc.)
     * @param string|null $value Input value
     * @param string|null $placeholder Input placeholder
     * @param bool $required Makes the input required
     * @param bool $disabled Disables the input
     * @param string|null $help Helper text below input
     * @param array|null $errors Validation errors
     */
    public function __construct(
        public string $name,
        public ?string $label = null,
        public string $type = 'text',
        public ?string $value = null,
        public ?string $placeholder = null,
        public bool $required = false,
        public bool $disabled = false,
        public ?string $help = null,
        public ?array $errors = null
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.input');
    }
}
