<?php

namespace MicroweberPackages\Livewire\Views\Filters;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class HiddenFilter extends Filter
{
    public function isEmpty(string $value): bool
    {
        return false;
    }

    public function render(DataTableComponent $component)
    {
        return '';
    }
}
