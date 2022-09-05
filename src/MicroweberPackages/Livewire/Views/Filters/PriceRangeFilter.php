<?php

namespace MicroweberPackages\Livewire\Views\Filters;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class PriceRangeFilter extends Filter
{
    protected array $options = [];

    public function options(array $options = []): PriceRangeFilter
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function validate($value)
    {
        return $value;
    }

    public function isEmpty($value): bool
    {
        return $value === '';
    }

    public function render(DataTableComponent $component)
    {

        if (!isset($component->state['minPrice'])) {
            $component->state['minPrice'] = 0;
        }
        if (!isset($component->state['maxPrice'])) {
            $component->state['maxPrice'] = 0;
        }

        $component->setFilter($this->key, $component->state['minPrice'] . ',' . $component->state['maxPrice']);

        return view('livewire::livewire.mw-livewire-tables.components.tools.filters.price-range', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
