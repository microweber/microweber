<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ProgressBar extends Component
{
    /**
     * Create the component instance.
     *
     * @param int $value Progress value (0-100)
     * @param string|null $type Progress type (success, info, warning, danger)
     * @param bool $striped Adds stripes
     * @param bool $animated Animates the stripes
     */
    public function __construct(
        public int $value = 0,
        public ?string $type = null,
        public bool $striped = false,
        public bool $animated = false
    ) {
        // Ensure value is between 0 and 100
        $this->value = max(0, min(100, $value));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.progress-bar');
    }
}
