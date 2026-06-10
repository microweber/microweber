<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FeatureItem extends Component
{
    public function __construct(
        public string $icon = '',
        public string $title = '',
        public string $text = '',
        public string $colClass = 'col-md-6 col-lg-4 col-12',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.feature-item');
    }
}