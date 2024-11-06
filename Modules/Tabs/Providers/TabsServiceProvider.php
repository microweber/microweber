<?php

namespace Modules\Tabs\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Tabs\Filament\TabsModuleSettings;
use Modules\Tabs\Filament\TabsTableList;
use Modules\Tabs\Microweber\TabsModule;

class TabsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Tabs';

    protected string $moduleNameLower = 'tabs';

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

        Livewire::component('modules.tabs.filament.tabs-table-list', TabsTableList::class);
        FilamentRegistry::registerPage(TabsModuleSettings::class);
        Microweber::module(TabsModule::class);
    }
}
