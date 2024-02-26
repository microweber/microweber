<?php

namespace MicroweberPackages\Modules\GoogleAnalytics\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

use MicroweberPackages\Modules\GoogleAnalytics\DispatchGoogleEventsJs;
use MicroweberPackages\Modules\GoogleAnalytics\Http\Livewire\Admin\AdminGoogleAnalyticsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GoogleAnalyticsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-google_analytics');
        $package->hasViews('microweber-module-google_analytics');
        $package->hasRoute('api');
        $package->hasRoute('web');
        $package->hasRoute('admin');
        $package->runsMigrations(true);
    }

    public function packageBooted()
    {
        $this->registerComponents();
    }

    public function registerComponents()
    {
        Blade::componentNamespace('MicroweberPackages\\Modules\\GoogleAnalytics\\View\\Components', 'google_analytics');

        View::addNamespace('google_analytics', normalize_path(__DIR__) . '/../resources/views');

        Livewire::component('google_analytics::admin-google-analytics', AdminGoogleAnalyticsComponent::class);

        return $this;
    }

    public function register(): void {

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $isGoogleMeasurementEnabled = get_option('google-measurement-enabled', 'website') == "y";
        if ($isGoogleMeasurementEnabled) {

            event_bind('mw.pingstats.response', function() {
                $dispatchGoogleEventsJs = new DispatchGoogleEventsJs();
                return $dispatchGoogleEventsJs->convertEvents();
            });
        }

        parent::register();

    }

}
