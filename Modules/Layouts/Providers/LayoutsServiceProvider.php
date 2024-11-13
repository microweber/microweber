<?php

namespace Modules\Layouts\Providers;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Layouts\Filament\LayoutsModuleSettings;
use Modules\Layouts\Microweber\LayoutsModule;

class LayoutsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Layouts';

    protected string $moduleNameLower = 'layouts';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


    }

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


        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(LayoutsModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Layouts\Microweber\LayoutsModule::class);
  //  use Filament\Support\Assets\Js;

//        FilamentAsset::register([
//            Js::make('layouts-module-settings', asset('modules/layouts/js/layouts-module-settings.js'))->module(),
//        ]);
//
//        FilamentAsset::register([
//            AlpineComponent::make('layouts-module-settings', __DIR__ . '/../resources/assets/js/layouts-module-settings.js'),
//        ]);

    }

}
