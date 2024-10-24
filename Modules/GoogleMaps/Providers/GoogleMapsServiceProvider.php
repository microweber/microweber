<?php

namespace Modules\GoogleMaps\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\GoogleMaps\Filament\GoogleMapsModuleSettings;
use Modules\GoogleMaps\Microweber\GoogleMapsModule;

class GoogleMapsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'GoogleMaps';

    protected string $moduleNameLower = 'googlemaps';



    /**
     * Register the service provider.
     */
    public function register(): void
    {


        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        FilamentRegistry::registerPage(GoogleMapsModuleSettings::class);
        Microweber::module('google_maps', GoogleMapsModule::class);
       // Microweber::module('googlemaps', GoogleMapsModule::class);


    }

}
