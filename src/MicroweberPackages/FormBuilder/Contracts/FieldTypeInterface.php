<?php

namespace MicroweberPackages\FormBuilder\Contracts;

use Filament\Schemas\Components\Component;

interface FieldTypeInterface
{
    /**
     * Resolve a field definition array into a Filament component.
     *
     * @param array $field The normalised field definition
     * @return Component
     */
    public function resolve(array $field): Component;
}
