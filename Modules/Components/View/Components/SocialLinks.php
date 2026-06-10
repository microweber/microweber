<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SocialLinks extends Component
{
    public function __construct(
        public string $size = 'md',
        public string $style = 'default',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.social-links');
    }
}