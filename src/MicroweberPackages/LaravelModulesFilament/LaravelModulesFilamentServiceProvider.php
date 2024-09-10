<?php

namespace MicroweberPackages\LaravelModulesFilament;

use Coolsam\Modules\ModulesPlugin;
use Coolsam\Modules\ModulesServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;

class LaravelModulesFilamentServiceProvider extends ModulesServiceProvider
{
    public function register(): void
    {
        parent::register();
        $this->mergeConfigFrom(
            __DIR__ . '/config/filament-modules.php',
            'filament-modules'
        );
        app()->register(ModulesPluginFilament::class);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();
    }

}
