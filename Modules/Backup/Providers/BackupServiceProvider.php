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
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
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
        parent::register();

        $this->registerConfig();
        $this->registerViews();
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

        // Register Filament resources and pages (task-2026-05-22-f83bf6 / AI-764).
        // The four backup entries live in a dedicated "Backup" nav-group with
        // shouldRegisterNavigation=false, so they're hidden from the sidebar and
        // surface only in the Backup section of the Settings page via the
        // Settings::class-scoped registrations below. Because path B (the nav-loop)
        // no longer captures them, the old duplicate-card issue can't recur.
        FilamentRegistry::registerResource(BackupResource::class);
        FilamentRegistry::registerResource(BackupScheduleResource::class);
        FilamentRegistry::registerResource(BackupHistoryResource::class);
        FilamentRegistry::registerPage(RestoreAdminPage::class);

        // Group all backup-related entries into a "Backup" section on the Settings page.
        $settingsHub = Settings::class;
        FilamentRegistry::registerResource(BackupResource::class, $settingsHub);
        FilamentRegistry::registerResource(BackupScheduleResource::class, $settingsHub);
        FilamentRegistry::registerResource(BackupHistoryResource::class, $settingsHub);
        FilamentRegistry::registerPage(RestoreAdminPage::class, $settingsHub);

        FilamentRegistry::registerGlobalSearchEntry(
            'Backup & Restore', '/admin/backups',
            ['backup', 'restore', 'backups', 'database backup',
             'site backup', 'export', 'import', 'data backup'],
            'Admin Pages', ['Section' => 'System'],
        );
    }

    /**
     * Boot the module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

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
