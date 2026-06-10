<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class VideoEmbed extends Component
{
    public function __construct(
        public string $url = '',
        public string $ratio = '16x9',
        public bool $autoplay = false,
        public string $height = '400',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.video-embed');
    }
}