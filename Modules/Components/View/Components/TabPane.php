<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TabPane extends Component
{
    /**
     * Create the component instance.
     *
     * @param string $title Tab title
     * @param bool $active Whether this tab is active
     */
    public function __construct(
        public string $title,
        public bool $active = false
    ) {}

    /**
     * Get a unique ID for this tab pane.
     */
    public function id(): string
    {
        return 'tab-' . str_replace(' ', '-', strtolower($this->title));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.tab-pane');
    }
}
