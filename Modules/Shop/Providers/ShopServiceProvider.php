<?php

namespace Modules\Shop\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Shop\Filament\ShopModuleSettings;
use Modules\Shop\Livewire\ShopComponent;
use Modules\Shop\Services\ShopManager;


class ShopServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Shop';

    protected string $moduleNameLower = 'shop';


    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        //$this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        $this->app->singleton('shop_manager', function ($app) {
            return new ShopManager();
        });

        Livewire::component('module-shop', ShopComponent::class);

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(ShopModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Shop\Microweber\ShopModule::class);

    }

}
