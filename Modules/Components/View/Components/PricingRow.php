<?php

namespace Modules\Components\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * A single pricing PLAN CARD (one column inside <x-pricing-table>).
 *
 * Despite the "row" name it renders a Bootstrap card (header + price + feature
 * list + CTA), not an HTML table `<tr>`. `:highlighted="true"` applies the
 * `border-primary` / `bg-primary` accent — primary resolves to the active
 * template's brand colour (Bootstrap blue, Big orange).
 *
 * NOTE: `features` is the ONLY array prop. Pass it bound (`:features="[...]"`).
 * Do NOT pass any OTHER bound array attribute the component doesn't declare —
 * undeclared array attributes fall through to `$attributes->merge()`, which
 * runs `trim()` on each value and fatals with "trim(): array given".
 */
class PricingRow extends Component
{
    public function __construct(
        public string $planName = '',
        public string $price = '',
        public string $period = '/mo',
        public array $features = [],
        public bool $highlighted = false,
        public string $buttonText = 'Choose plan',
        public string $buttonStyle = 'btn btn-outline-primary',
        public string $class = ''
    ) {}

    public function render(): View
    {
        return view('modules.components::components.pricing-row');
    }
}