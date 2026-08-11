<?php

namespace Modules\TextType\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use Modules\TextType\Filament\TextTypeModuleSettings;
use Modules\TextType\Microweber\TextTypeModule;

class TextTypeServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'TextType';

    protected string $moduleNameLower = 'text_type';

    public function boot(): void
    {
    }

    public function register(): void
    {
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        FilamentRegistry::registerPage(TextTypeModuleSettings::class);
        ModuleRegistry::module(\Modules\TextType\Microweber\TextTypeModule::class);
    }
}
