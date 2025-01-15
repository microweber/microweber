<?php

namespace Modules\Slider\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Slider\Filament\SliderModuleSettings;
use Modules\Slider\Filament\SliderTableList;
use Modules\Slider\Microweber\SliderModule;

class SliderServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Slider';

    protected string $moduleNameLower = 'slider';

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

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(SliderModuleSettings::class);

        Livewire::component('modules.slider.filament.slider-table-list', SliderTableList::class);

        // Register Microweber module
        Microweber::module(\Modules\Slider\Microweber\SliderModule::class);
    }
}
