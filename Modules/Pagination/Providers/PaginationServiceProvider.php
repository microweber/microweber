<?php

namespace Modules\Pagination\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Pagination\Filament\PaginationModuleSettings;
use Modules\Pagination\Microweber\PaginationModule;

class PaginationServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Pagination';

    protected string $moduleNameLower = 'pagination';



    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
       // $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(PaginationModuleSettings::class);

        // Register Microweber module
        Microweber::module(PaginationModule::class);
    }

}
