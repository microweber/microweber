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
use Modules\Backup\BackupManager;
use Illuminate\Contracts\Support\DeferrableProvider;
use Modules\Backup\Filament\Resources\BackupResource;


class BackupServiceProvider extends ServiceProvider implements DeferrableProvider
{
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
        $this->mergeConfigFrom(
            __DIR__.'/../config/backup.php', 'backup'
        );

        FilamentRegistry::registerResource(BackupResource::class);
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
