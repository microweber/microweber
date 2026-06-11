<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PriceListItem extends Component
{
    public function __construct(
        public string $title = '',
        public string $description = '',
        public string $price = '',
        public bool $showDivider = true,
    ) {}

    public function render(): View
    {
        return view('modules.components::components.price-list-item');
    }
}