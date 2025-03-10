<?php

namespace Modules\Spacer\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Spacer\Filament\SpacerModuleSettings;
use Modules\Spacer\Microweber\SpacerModule;

class SpacerServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Spacer';

    protected string $moduleNameLower = 'spacer';

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
       // $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(SpacerModuleSettings::class);

        // Register Microweber module
        Microweber::module(SpacerModule::class);
    }
}
