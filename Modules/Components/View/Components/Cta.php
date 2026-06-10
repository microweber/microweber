<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Cta extends Component
{
    public function __construct(
        public string $align = 'center',
        public string $layout = 'stacked',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.cta');
    }
}