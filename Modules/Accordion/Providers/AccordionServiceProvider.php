<?php

namespace Modules\Accordion\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use Modules\Accordion\Filament\AccordionModuleSettings;
use Modules\Accordion\Filament\AccordionTableList;
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
        parent::register();


        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        Livewire::component('modules.accordion.filament.accordion-table-list', AccordionTableList::class);
        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(AccordionModuleSettings::class);

        // Register Microweber module
        ModuleRegistry::module(AccordionModule::class);

    }

}
