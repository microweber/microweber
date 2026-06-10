<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TeamCard extends Component
{
    public function __construct(
        public string $name = '',
        public string $role = '',
        public string $bio = '',
        public string $image = '',
        public string $website = '',
        public string $class = ''
    ) {}

    public function render(): View
    {
        return view('modules.components::components.team-card');
    }
}