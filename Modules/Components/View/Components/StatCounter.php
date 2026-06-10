<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class StatCounter extends Component
{
    public function __construct(
        public string $value = '0',
        public string $label = '',
        public string $suffix = '',
        public string $prefix = '',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.stat-counter');
    }
}