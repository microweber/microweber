<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Accordion extends Component
{
    public string $accordionId;

    public function __construct(
        public bool $flush = false,
        ?string $id = null,
    ) {
        $this->accordionId = $id ?? 'accordion-' . uniqid();
    }

    public function render(): View
    {
        return view('modules.components::components.accordion');
    }
}