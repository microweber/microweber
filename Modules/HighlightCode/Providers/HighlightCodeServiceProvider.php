<?php

namespace Modules\HighlightCode\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\HighlightCode\Filament\HighlightCodeModuleSettings;
use Modules\HighlightCode\Microweber\HighlightCodeModule;

class HighlightCodeServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'HighlightCode';

    protected string $moduleNameLower = 'highlight_code';

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
        FilamentRegistry::registerPage(HighlightCodeModuleSettings::class);

        // Register Microweber module
        Microweber::module('highlight_code', \Modules\HighlightCode\Microweber\HighlightCodeModule::class);

    }

}
