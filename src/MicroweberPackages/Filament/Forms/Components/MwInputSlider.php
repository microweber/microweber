<?php
namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\HasStateBindingModifiers;

class MwInputSlider extends TextInput
{
    protected string $view = 'filament-forms::components.mw-hidden-input';

    public static function make(string $name): static
    {
        $static = parent::make($name);

        $static->default(0);

        return $static;
    }
}
