<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Modal extends Component
{
    /**
     * Create the component instance.
     *
     * @param string $id Modal identifier
     * @param string|null $title Modal title
     * @param string|null $size Modal size (sm, lg, xl)
     * @param bool $centered Whether to vertically center the modal
     * @param bool $scrollable Whether the modal body should be scrollable
     * @param bool $dismissible Whether to render a close (×) button (shown even without a title)
     */
    public function __construct(
        public string $id,
        public ?string $title = null,
        public ?string $size = null,
        public bool $centered = false,
        public bool $scrollable = false,
        public bool $dismissible = true
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.modal');
    }
}
