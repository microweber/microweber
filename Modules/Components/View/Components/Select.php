<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Select extends Component
{
    /**
     * Create the component instance.
     *
     * @param string $name Select name
     * @param string|null $label Select label
     * @param array $options Array of options
     * @param string|array|null $selected Selected value(s)
     * @param bool $multiple Allows multiple selections
     */
    public function __construct(
        public string $name,
        public ?string $label = null,
        public array $options = [],
        public $selected = null,
        public bool $multiple = false
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.select');
    }
}
