<?php

namespace Modules\Btn\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Audio\Filament\AudioModuleSettings;
use Modules\Btn\Filament\BtnModuleSettings;

class BtnServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Btn';

    protected string $moduleNameLower = 'btn';

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


        FilamentRegistry::registerPage(BtnModuleSettings::class);
        Microweber::module( \Modules\Btn\Microweber\BtnModule::class);

    }

}
