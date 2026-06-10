<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SectionHeading extends Component
{
    public function __construct(
        public string $tag = 'h2',
        public string $subtitle = '',
        public string $align = 'center',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.section-heading');
    }
}