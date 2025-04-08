<?php

namespace MicroweberPackages\Multilanguage\Filament\Resources\Concerns;

use Filament\Resources\Concerns\HasActiveLocaleSwitcher;
use Filament\SpatieLaravelTranslatableContentDriver;
use Filament\Support\Contracts\TranslatableContentDriver;

trait TranslatableHasActiveLocaleSwitcher
{
     use HasActiveLocaleSwitcher;
}
