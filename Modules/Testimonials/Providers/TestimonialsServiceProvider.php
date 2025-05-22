<?php

namespace Modules\Testimonials\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
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

        Livewire::component('modules.testimonials.filament.testimonials-table-list', TestimonialsTableList::class);
        FilamentRegistry::registerPage(TestimonialsModuleSettings::class);
        Microweber::module(TestimonialsModule::class);





    }
}
