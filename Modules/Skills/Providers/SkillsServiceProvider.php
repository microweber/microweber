<?php

namespace Modules\Skills\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use Modules\Skills\Filament\SkillsModuleSettings;
use Modules\Skills\Microweber\SkillsModule;

class SkillsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Skills';

    protected string $moduleNameLower = 'skills';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // task-2026-09-15-qskit — Skills item CRUD API for the Live-Edit inline list.
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

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(SkillsModuleSettings::class);

        // Register Microweber module
        ModuleRegistry::module(\Modules\Skills\Microweber\SkillsModule::class);

    }

}
