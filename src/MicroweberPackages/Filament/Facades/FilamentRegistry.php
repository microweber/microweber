<?php

namespace MicroweberPackages\Filament\Facades;

use MicroweberPackages\Filament\FilamentRegistryManager;

class FilamentRegistry
{

    protected static function getFacadeAccessor(): string
    {
        return FilamentRegistryManager::class;
    }
}
