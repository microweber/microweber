<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TestimonialCard extends Component
{
    public function __construct(
        public string $name = '',
        public string $content = '',
        public string $image = '',
        public string $company = '',
        public string $role = '',
        public string $class = ''
    ) {}

    public function render(): View
    {
        return view('modules.components::components.testimonial-card');
    }
}