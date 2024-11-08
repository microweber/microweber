<?php

namespace Modules\Content\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Content\Filament\ContentTableList;
use Modules\Content\Microweber\ContentModule;

class ContentServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Content';

    protected string $moduleNameLower = 'content';

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

        Livewire::component('modules.content.filament.content-table-list', ContentTableList::class);
        FilamentRegistry::registerResource(ContentResource::class);
        FilamentRegistry::registerPage(ContentModuleSettings::class);
        Microweber::module(ContentModule::class);
    }
}
