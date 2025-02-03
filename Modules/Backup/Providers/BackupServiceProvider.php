<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace Modules\Backup\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Backup\BackupManager;
use Illuminate\Contracts\Support\DeferrableProvider;
use Modules\Backup\Filament\Pages\CreateBackup;
use Modules\Backup\Filament\Resources\BackupResource;


class BackupServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Backup';

    protected string $moduleNameLower = 'backup';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations/');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();

        $this->mergeConfigFrom(
            __DIR__.'/../config/backup.php', 'backup'
        );

        FilamentRegistry::registerResource(BackupResource::class);
        FilamentRegistry::registerPage(CreateBackup::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['backup_manager'];
    }

}
