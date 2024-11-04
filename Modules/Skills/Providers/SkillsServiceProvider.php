<?php

namespace Modules\Skills\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
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


    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(SkillsModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Skills\Microweber\SkillsModule::class);

    }

}
