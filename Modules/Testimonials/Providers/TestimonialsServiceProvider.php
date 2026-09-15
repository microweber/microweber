<?php

namespace Modules\Testimonials\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use Modules\Testimonials\Filament\TestimonialsModuleSettings;
use Modules\Testimonials\Filament\TestimonialsTableList;
use Modules\Testimonials\Microweber\TestimonialsModule;

class TestimonialsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Testimonials';

    protected string $moduleNameLower = 'testimonials';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // task-2026-09-15-qskit — Testimonials item CRUD API for the Live-Edit inline list.
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
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

        Livewire::component('modules.testimonials.filament.testimonials-table-list', TestimonialsTableList::class);
        FilamentRegistry::registerPage(TestimonialsModuleSettings::class);
        ModuleRegistry::module(TestimonialsModule::class);





    }
}
