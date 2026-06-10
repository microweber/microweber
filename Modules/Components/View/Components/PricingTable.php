<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Pricing plan-card GRID (not an HTML compare-table).
 *
 * `<x-pricing-table>` is a thin `row row-cols-1 row-cols-md-{columns}` wrapper
 * whose slot holds one `<x-pricing-row>` per plan. Despite the name, it models
 * the responsive CARD GRID of pricing plans — the standalone HTML
 * "Compare plans" table some skins render below the grid is plain markup, not
 * this component.
 *
 * Usage:
 *   <x-pricing-table :columns="3">
 *       <x-pricing-row plan-name="Free" price="$0" :features="['10 users']" />
 *       ...
 *   </x-pricing-table>
 */
class PricingTable extends Component
{
    public function __construct(
        public string $class = '',
        public int $columns = 3
    ) {}

    public function render(): View
    {
        return view('modules.components::components.pricing-table');
    }
}