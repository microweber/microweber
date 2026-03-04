<?php

namespace MicroweberPackages\Multilanguage\Filament\Resources\Concerns;

use LaraZeus\SpatieTranslatable\Resources\Concerns\HasActiveLocaleSwitcher;
use Filament\SpatieLaravelTranslatableContentDriver;
use Filament\Support\Contracts\TranslatableContentDriver;

trait TranslatableHasActiveLocaleSwitcher
{
     use HasActiveLocaleSwitcher;


    public function getFilamentTranslatableContentDriver(): ?string
    {
        return MwSpatieLaravelTranslatableContentDriver::class;
    }
}
