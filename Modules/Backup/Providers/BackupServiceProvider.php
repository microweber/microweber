<?php

namespace Modules\Backup\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Backup\Filament\Admin\BackupResource;
use Modules\Backup\Support\GenerateBackup;


class BackupServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Backup';

    protected string $moduleNameLower = 'backup';

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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
//        $this->app->bind('backup', function () {
//            return new GenerateBackup();
//        });

        // Register filament page for Microweber module settings
//        FilamentRegistry::registerResource(BackupResource::class);

        // Register Microweber module
        // Microweber::module(\Modules\Backup\Microweber\BackupModule::class);

    }

}
