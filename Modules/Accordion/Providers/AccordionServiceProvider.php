<?php

namespace Modules\Accordion\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Accordion\Filament\AccordionModuleSettings;
use Modules\Accordion\Microweber\AccordionModule;

class AccordionServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Accordion';

    protected string $moduleNameLower = 'accordion';

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
        FilamentRegistry::registerPage(AccordionModuleSettings::class);

        // Register Microweber module
        Microweber::module('accordion', AccordionModule::class);

    }

}
