<?php

namespace Modules\Rating\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Rating\Filament\RatingModuleSettings;
use Modules\Rating\Filament\Resources\RatingModuleResource;
use Modules\Rating\Microweber\RatingModule;

class RatingServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Rating';
    protected string $moduleNameLower = 'rating';

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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(RatingModuleSettings::class);

        // Register Filament resource
        FilamentRegistry::registerResource(RatingModuleResource::class);

        // Register Microweber module
        Microweber::module(RatingModule::class);
    }
}
