<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GalleryGrid extends Component
{
    public function __construct(
        public string $cols = '3',
        public string $gap = 'g-3',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.gallery-grid');
    }
}