<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Closure;
use Filament\Support\Contracts\HasLabel as LabelInterface;
use Illuminate\Contracts\Support\Arrayable;

class MwColorPicker extends ColorPicker
{
    protected string $view = 'filament-forms::components.mw-color-picker';
}
