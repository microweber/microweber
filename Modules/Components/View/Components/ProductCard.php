<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ProductCard extends Component
{
    public function __construct(
        public string $title = '',
        public string $image = '',
        public string $link = '',
        public string $price = '',
        public string $originalPrice = '',
        public string $description = '',
        public bool $inStock = true,
        public string $addToCartText = 'Add to cart',
        public string|int $contentId = '',
        public string $class = ''
    ) {}

    public function render(): View
    {
        return view('modules.components::components.product-card');
    }
}