<?php
/*
* This file is part of the Microweber framework.
*
* (c) Microweber CMS LTD
*
* For full license information see
* https://github.com/microweber/microweber/blob/master/LICENSE
*/

namespace Modules\Backup\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

use Modules\Backup\Console\Commands\BackupCommand;
use Modules\Backup\Filament\Resources\BackupResource;
use Modules\Backup\Filament\Resources\BackupScheduleResource;
use Modules\Backup\Filament\Resources\BackupHistoryResource;
use Modules\Settings\Filament\Pages\Settings;


class BackupServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Backup';

    protected string $moduleNameLower = 'backup';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Database/migrations/');

        $this->mergeConfigFrom(
            __DIR__.'/../config/backup.php', 'backup'
        );

        // Register console commands
        $this->commands([
            BackupCommand::class,
        ]);

        // Register Filament resources
        FilamentRegistry::registerResource(BackupResource::class);
        FilamentRegistry::registerResource(BackupResource::class, Settings::class);
        FilamentRegistry::registerResource(BackupScheduleResource::class);
        FilamentRegistry::registerResource(BackupHistoryResource::class);
    }

    /**
     * Boot the module.
     *
     * @return void
     */
    public function boot(): void
    {
        // Configure backup filesystem disk
        Config::set('filesystems.disks.backup', [
            'driver' => 'local',
            'root' => storage_path() . '/backup_content/' . \App::environment() . '/',
            'visibility' => 'private',
        ]);

        // Schedule automated backups
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Run backup schedules every minute
            $schedule->command('backup:run')->everyMinute()->name('backup-schedules');

            // Clean up stale backups once per day
            $schedule->command('backup:run --cleanup')->daily()->name('backup-cleanup');
        });
    }
}
