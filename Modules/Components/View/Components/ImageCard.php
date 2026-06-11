<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ImageCard extends Component
{
    public function __construct(
        public string $src = '',
        public string $alt = '',
        public string $wrapperClass = 'img-as-background square',
        public string $imgClass = '',
        public bool $lazy = true,
    ) {}

    public function render(): View
    {
        return view('modules.components::components.image-card');
    }
}