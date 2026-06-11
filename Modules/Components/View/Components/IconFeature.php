<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class IconFeature extends Component
{
    public function __construct(
        public string $icon = '',
        public string $title = '',
        public string $text = '',
        public string $iconSize = '40px',
        public string $layout = 'horizontal',
        public string $colClass = '',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.icon-feature');
    }
}