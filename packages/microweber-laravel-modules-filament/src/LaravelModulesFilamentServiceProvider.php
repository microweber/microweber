<?php

namespace MicroweberPackages\LaravelModulesFilament;

use Coolsam\Modules\ModulesPlugin;
use Coolsam\Modules\ModulesServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;

class LaravelModulesFilamentServiceProvider extends ModulesServiceProvider
{
    use \MicroweberPackages\ConfigMerge\MergesConfigFromPackage;

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
