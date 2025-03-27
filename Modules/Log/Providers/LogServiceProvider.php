<?php

namespace Modules\Log\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;


class LogServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Log';

    protected string $moduleNameLower = 'log';

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

        $this->app->singleton('log_manager', function ($app) {
            return new \Modules\Log\Services\LogManager();
        });
        $this->app->bind(\MicroweberPackages\App\Managers\LogManager::class, \Modules\Log\Services\LogManager::class);

        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(LogModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Log\Microweber\LogModule::class);

    }

}
