<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Tabs extends Component
{
    /**
     * Create the component instance.
     *
     * @param bool $vertical Creates vertical tabs
     * @param bool $pills Uses pill style
     */
    public function __construct(
        public bool $vertical = false,
        public bool $pills = false
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.tabs');
    }
}
