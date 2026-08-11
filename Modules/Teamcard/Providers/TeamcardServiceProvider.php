<?php

namespace Modules\Teamcard\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use Modules\Teamcard\Filament\TeamcardModuleSettings;
use Modules\Teamcard\Filament\TeamcardTableList;
use Modules\Teamcard\Microweber\TeamcardModule;

class TeamcardServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Teamcard';

    protected string $moduleNameLower = 'teamcard';

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
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(TeamcardModuleSettings::class);

        Livewire::component('modules.teamcard.filament.teamcard-table-list', TeamcardTableList::class);

        // Register Microweber module
        ModuleRegistry::module( \Modules\Teamcard\Microweber\TeamcardModule::class);

    }

}
