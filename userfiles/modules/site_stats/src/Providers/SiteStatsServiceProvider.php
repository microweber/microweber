<?php

namespace MicroweberPackages\Modules\SiteStats\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Livewire\Livewire;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasRegisteredListener;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\SiteStats\Http\Livewire\SiteStatsSettingsComponent;

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
        ModuleAdmin::registerSettings('site_stats', 'microweber-module-sitestats::settings');

        // if google or fb pixel is enabled
        $this->app->register(\MicroweberPackages\Modules\SiteStats\Providers\UtmTrackingEventsServiceProvider::class);

        $this->app->register(\MicroweberPackages\Modules\SiteStats\Providers\SiteStatsEventsLocalTrackingServiceProvider::class);
    }

}
