<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MediaCard extends Component
{
    public function __construct(
        public string $title = '',
        public string $description = '',
        public string $image = '',
        public string $link = '',
        public string $mediaType = 'image',
        public string $class = ''
    ) {}

    public function render(): View
    {
        return view('modules.components::components.media-card');
    }
}