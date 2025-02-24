<?php

namespace Modules\Blog\Providers;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Blog\Filament\BlogSettings;
use Modules\Blog\Livewire\BlogComponent;
use Modules\Blog\Microweber\BlogModule;

class BlogServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Blog';
    protected string $moduleNameLower = 'blog';

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
        Livewire::component('module-blog', BlogComponent::class);

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(BlogSettings::class);

        // Register Microweber module
        Microweber::module(BlogModule::class);
    }


}
