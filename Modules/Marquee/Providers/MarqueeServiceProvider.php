<?php

namespace Modules\Marquee\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use Modules\Marquee\Filament\MarqueeModuleSettings;
use Modules\Marquee\Microweber\MarqueeModule;

class MarqueeServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Marquee';

    protected string $moduleNameLower = 'marquee';

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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(MarqueeModuleSettings::class);

        // Register Microweber module
        ModuleRegistry::module(\Modules\Marquee\Microweber\MarqueeModule::class);

    }

}
