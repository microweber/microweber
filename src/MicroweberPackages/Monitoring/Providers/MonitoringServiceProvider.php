<?php

namespace MicroweberPackages\Monitoring\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Monitoring\Services\ErrorTrackingService;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Monitoring\Filament\Resources\ErrorTrackingResource;
use MicroweberPackages\Monitoring\Filament\Widgets\ErrorStatsWidget;
use MicroweberPackages\Monitoring\Console\Commands\BootQueryAuditCommand;
use MicroweberPackages\Monitoring\Console\Commands\CleanupErrorTracking;
use MicroweberPackages\Monitoring\Console\Commands\ConfigOrphanAuditCommand;
use MicroweberPackages\Monitoring\Console\Commands\ExportErrorReport;
use MicroweberPackages\Monitoring\Console\Commands\Psr4StrictAuditCommand;

class MonitoringServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ErrorTrackingService::class, function ($app) {
            return new ErrorTrackingService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register Filament resources
        FilamentRegistry::registerResource(ErrorTrackingResource::class);

        // Register Filament widgets
        FilamentRegistry::registerWidget(ErrorStatsWidget::class);

        // AI-120 / TICKET-BN (cycle-117): register the boot-time
        // query audit command alongside the existing monitoring
        // commands. CLI-only — gated by $this->app->runningInConsole().
        if ($this->app->runningInConsole()) {
            $this->commands([
                BootQueryAuditCommand::class,
                CleanupErrorTracking::class,
                // AI-124 / TICKET-CT + TICKET-CU (cycle-121).
                ConfigOrphanAuditCommand::class,
                Psr4StrictAuditCommand::class,
                ExportErrorReport::class,
            ]);
        }
    }
}
