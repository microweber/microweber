<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PostCard extends Component
{
    public function __construct(
        public string $title = '',
        public string $description = '',
        public string $image = '',
        public string $link = '',
        public string $date = '',
        public string $author = '',
        public string $readMoreText = 'Read more',
        public string $class = ''
    ) {}

    public function render(): View
    {
        return view('modules.components::components.post-card');
    }
}