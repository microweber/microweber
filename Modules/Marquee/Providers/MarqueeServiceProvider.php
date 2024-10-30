<?php

namespace Modules\Marquee\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
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
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(MarqueeModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Marquee\Microweber\MarqueeModule::class);

    }

}
