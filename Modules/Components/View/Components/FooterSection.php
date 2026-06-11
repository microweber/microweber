<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FooterSection extends Component
{
    public function __construct(
        public string $copyrightField = '',
        public string $sectionId = '',
    ) {}

    public function render(): View
    {
        return view('modules.components::components.footer-section');
    }
}