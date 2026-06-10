<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AccordionItem extends Component
{
    public string $itemId;

    public function __construct(
        public string $title = '',
        public bool $open = false,
        public string $parent = '',
        ?string $id = null,
    ) {
        $this->itemId = $id ?? 'accordion-item-' . uniqid();
    }

    public function render(): View
    {
        return view('modules.components::components.accordion-item');
    }
}