<?php

namespace Modules\AiWizard\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\AiWizard\Livewire\SectionProcessor;



class AiWizardServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'AiWizard';

    protected string $moduleNameLower = 'aiwizard';

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
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(AiWizardModuleSettings::class);
        FilamentRegistry::registerResource(\Modules\AiWizard\Filament\Admin\AiWizardResource::class);
        Livewire::component('modules.aiwizard::section-processor', SectionProcessor::class);

        // Register Microweber module
        // Microweber::module(\Modules\AiWizard\Microweber\AiWizardModule::class);

    }


}
