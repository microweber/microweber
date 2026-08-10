<?php

namespace Modules\Pictures\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Pictures\Filament\PicturesModuleSettings;
use Modules\Pictures\Filament\PicturesTableList;
use Modules\Pictures\Microweber\PicturesModule;

class PicturesServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Pictures';

    protected string $moduleNameLower = 'pictures';

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

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(PicturesModuleSettings::class);
        Livewire::component('modules.pictures.filament.pictures-table-list', PicturesTableList::class);

        // Register Microweber module
        Microweber::module(\Modules\Pictures\Microweber\PicturesModule::class);
    }
}
