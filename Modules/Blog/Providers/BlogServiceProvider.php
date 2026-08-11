<?php

namespace Modules\Blog\Providers;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // Register Livewire component
        Livewire::component('module-blog', BlogComponent::class);

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(BlogSettings::class);

        // Register Microweber module
        ModuleRegistry::module(BlogModule::class);
    }


}
