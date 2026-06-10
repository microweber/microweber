<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Tab extends Component
{
    public string $tabId;

    public function __construct(
        public bool $pills = false,
        public bool $vertical = false,
        ?string $id = null,
    ) {
        $this->tabId = $id ?? 'tab-' . uniqid();
    }

    public function render(): View
    {
        return view('modules.components::components.tab');
    }
}