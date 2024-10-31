<?php

namespace Modules\Embed\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Embed\Filament\EmbedModuleSettings;
use Modules\Embed\Microweber\EmbedModule;

class EmbedServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Embed';

    protected string $moduleNameLower = 'embed';

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
        FilamentRegistry::registerPage(EmbedModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Embed\Microweber\EmbedModule::class);

    }

}
