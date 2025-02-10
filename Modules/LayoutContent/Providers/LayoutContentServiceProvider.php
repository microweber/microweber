<?php

namespace Modules\LayoutContent\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Testimonials\Microweber\LayoutContentModule;


class LayoutContentServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'LayoutContent';

    protected string $moduleNameLower = 'layout_content';

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
        // FilamentRegistry::registerPage(LayoutContentModuleSettings::class);

        // Register Microweber module
         Microweber::module(LayoutContentModule::class);

    }

}
