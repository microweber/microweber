<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Card extends Component
{
    /**
     * Create the component instance.
     *
     * @param string|null $headerClass Additional classes for header
     * @param string|null $bodyClass Additional classes for body
     * @param string|null $footerClass Additional classes for footer
     */
    public function __construct(
        public ?string $headerClass = null,
        public ?string $bodyClass = null,
        public ?string $footerClass = null
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.card');
    }
}
