<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;

class Pagination extends Component
{
    /**
     * Create the component instance.
     *
     * @param mixed $items The paginator instance
     * @param string|null $size Pagination size (sm, lg)
     */
    public function __construct(
        public $items,
        public ?string $size = null
    ) {
        if (!($items instanceof Paginator || $items instanceof LengthAwarePaginator)) {
            throw new \InvalidArgumentException('The items parameter must be a paginator instance.');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('modules.components::components.pagination');
    }
}
