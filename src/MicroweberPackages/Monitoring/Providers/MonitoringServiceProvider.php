<?php

namespace MicroweberPackages\Monitoring\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Monitoring\Services\ErrorTrackingService;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Monitoring\Filament\Resources\ErrorTrackingResource;
use MicroweberPackages\Monitoring\Filament\Widgets\ErrorStatsWidget;

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
    }
}
