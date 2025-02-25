<?php

namespace Modules\Search\Providers;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Search\Filament\SearchSettings;
use Modules\Search\Livewire\SearchComponent;
use Modules\Search\Microweber\SearchModule;

class SearchServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Search';
    protected string $moduleNameLower = 'search';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // Boot logic
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

        // Register Livewire component
        Livewire::component('module-search', SearchComponent::class);

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(SearchSettings::class);

        // Register Microweber module
        Microweber::module(SearchModule::class);
    }
}
