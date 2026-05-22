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
use Modules\Backup\Console\Commands\Big2DemoSeedCommand;
use Modules\Backup\Console\Commands\Big2InstallContentCommand;
use Modules\Backup\Console\Commands\ShopDemoSeedCommand;
use Modules\Backup\Console\Commands\TemplateSeedRegenerateCommand;
use Modules\Backup\Filament\Pages\RestoreAdminPage;
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
            // AI-101 + AI-103 (cycle-141 2026-05-09): operational fix path
            // for the Big2 mw_default_content.zip seed regeneration.
            TemplateSeedRegenerateCommand::class,
            // Cycle-157 (2026-05-10): Big2 demo-page seeder for mobile
            // audits — `php artisan mw:big2-demo-seed`.
            Big2DemoSeedCommand::class,
            // Cycle-159 (2026-05-10): shop demo seeder — populates a
            // category + N products + /shop page for mobile-audit
            // testing of the Big2 Ecommerce layouts (AI-171).
            ShopDemoSeedCommand::class,
            // task-2026-05-13-3330a0 — Big2 full-content seeder, restores
            // the canonical mw_default_content.zip via TemplateInstaller
            // so tester-agent-1 has a realistic Big2 surface to evaluate.
            Big2InstallContentCommand::class,
        ]);

        // Register Filament resources and pages (task-2026-05-22-f83bf6 / AI-764)
        // task-2026-05-22-AI-929 — keep only global registration; the Settings hub nav-loop
        // already captures BackupResource from the "System Settings" nav-group (ends with "Settings").
        // The previously-added Settings::class-scoped registration caused a duplicate card because
        // the nav-loop extracted a blank slug while the resource produced a real slug — dedup failed.
        FilamentRegistry::registerResource(BackupResource::class);
        FilamentRegistry::registerResource(BackupScheduleResource::class);
        FilamentRegistry::registerResource(BackupHistoryResource::class);
        FilamentRegistry::registerPage(RestoreAdminPage::class);
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
