<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TabItem extends Component
{
    public string $itemId;

    public function __construct(
        public string $title = '',
        public bool $active = false,
        public string $parent = '',
        ?string $id = null,
    ) {
        $this->itemId = $id ?? 'tab-item-' . uniqid();
    }

    public function render(): View
    {
        return view('modules.components::components.tab-item');
    }
}