<?php
namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class MwInputSlider extends Field
{
    protected string $view = 'filament-forms::components.mw-hidden-input';

    public static function make(string $name): static
    {
        $static = parent::make($name);

        $static->default(0);

        return $static;
    }
}
