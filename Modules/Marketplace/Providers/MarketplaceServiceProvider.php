<?php

namespace Modules\Marketplace\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Marketplace\Filament\Admin\ListLicenses;
use Modules\Marketplace\Filament\Admin\MarketplaceResource;
use Modules\WhiteLabel\Filament\Admin\WhiteLabelLicenseManager;


class MarketplaceServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Marketplace';

    protected string $moduleNameLower = 'marketplace';

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

        FilamentRegistry::registerResource(MarketplaceResource::class);
        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(MarketplaceModuleSettings::class);
        Livewire::component('admin-list-licenses', ListLicenses::class);


        //


        // Register Microweber module
        // Microweber::module(\Modules\Marketplace\Microweber\MarketplaceModule::class);

    }

}
