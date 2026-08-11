<?php

namespace Modules\GoogleMaps\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use Modules\GoogleMaps\Filament\GoogleMapsModuleSettings;
use Modules\GoogleMaps\Microweber\GoogleMapsModule;

class GoogleMapsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'GoogleMaps';

    protected string $moduleNameLower = 'google_maps';



    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();



        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        FilamentRegistry::registerPage(GoogleMapsModuleSettings::class);
        ModuleRegistry::module( GoogleMapsModule::class);


    }

}
