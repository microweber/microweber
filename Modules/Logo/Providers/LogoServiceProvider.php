<?php

namespace Modules\Logo\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Logo\Filament\LogoModuleSettings;
use Modules\Logo\Microweber\LogoModule;

class LogoServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Logo';

    protected string $moduleNameLower = 'logo';

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
        FilamentRegistry::registerPage(LogoModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Logo\Microweber\LogoModule::class);

    }

}
