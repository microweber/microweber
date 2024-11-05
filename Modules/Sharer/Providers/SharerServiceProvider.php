<?php

namespace Modules\Sharer\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Sharer\Filament\SharerModuleSettings;
use Modules\Sharer\Microweber\SharerModule;

class SharerServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Sharer';

    protected string $moduleNameLower = 'sharer';

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

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(SharerModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Sharer\Microweber\SharerModule::class);

    }

}
