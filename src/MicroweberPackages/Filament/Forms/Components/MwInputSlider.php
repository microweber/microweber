<?php
namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\HasStateBindingModifiers;

class MwInputSlider extends TextInput
{
    protected string $view = 'mw-filament::components.mw-hidden-input';

    public static function make(string $name): static
    {
        $static = parent::make($name);

        return $static;
    }

    public function getCurrentState()
    {
        return $this->getState() ?? $this->getDefaultState() ?? 0;
    }
}
