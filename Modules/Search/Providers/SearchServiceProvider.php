<?php

namespace Modules\Search\Providers;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // task-2026-05-17-3e91f4 / AI-837 — load the frontend /search
        // route handler. BaseModuleServiceProvider does NOT auto-load
        // module routes (see scaffold/provider.stub:37 commented stub);
        // explicit opt-in is required so /search?q=X is served by
        // SearchController instead of falling through to the
        // FrontendController catch-all + clean.blade.php "My title /
        // My text content" placeholder. Stage-3 closure.
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        // Register Livewire component
        Livewire::component('module-search', SearchComponent::class);

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(SearchSettings::class);

        // Register Microweber module
        ModuleRegistry::module(SearchModule::class);
    }
}
