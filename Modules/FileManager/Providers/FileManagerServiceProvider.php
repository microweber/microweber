<?php

namespace Modules\FileManager\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;


class FileManagerServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'FileManager';

    protected string $moduleNameLower = 'filemanager';

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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(FileManagerModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\FileManager\Microweber\FileManagerModule::class);

    }

}
