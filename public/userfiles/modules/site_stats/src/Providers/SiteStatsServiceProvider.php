<?php

namespace MicroweberPackages\Modules\SiteStats\Providers;

use App\Filament\Admin\Pages\Dashboard;
use Filament\Events\ServingFilament;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasRegisteredListener;
use MicroweberPackages\Modules\SiteStats\Filament\SiteStatsDashboard;
use MicroweberPackages\Modules\SiteStats\Filament\SiteStatsDashboardChart;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\SiteStats\Http\Livewire\SiteStatsSettingsComponent;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class SiteStatsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-sitestats');
        $package->hasViews('microweber-module-sitestats');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-sitestats::settings', SiteStatsSettingsComponent::class);
        Livewire::component('microweber-module-sitestats::dashboard', SiteStatsDashboard::class);
        Livewire::component('microweber-module-sitestats::dashboard-chart', SiteStatsDashboardChart::class);


        ModuleAdmin::registerSettings('site_stats', 'microweber-module-sitestats::settings');


        FilamentRegistry::registerWidget(
            SiteStatsDashboardChart::class,
            \App\Filament\Admin\Pages\Dashboard::class);

        FilamentRegistry::registerWidget(
            SiteStatsDashboard::class,
            \App\Filament\Admin\Pages\Dashboard::class);



        // if google or fb pixel is enabled
        $this->app->register(\MicroweberPackages\Modules\SiteStats\Providers\UtmTrackingEventsServiceProvider::class);
        $this->app->register(\MicroweberPackages\Modules\SiteStats\Providers\SiteStatsEventsLocalTrackingServiceProvider::class);
    }

}
