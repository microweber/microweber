<?php

namespace MicroweberPackages\Modules\SiteStats\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Livewire\Livewire;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasRegistered;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\SiteStats\Http\Livewire\SiteStatsSettingsComponent;

class SiteStatsServiceProvider extends PackageServiceProvider
{
    protected $listen = [
        Login::class => [

        ],
        Registered::class => [
            UserWasRegistered::class,
        ],
    ];

    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-sitestats');
        $package->hasViews('microweber-module-sitestats');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-sitestats::settings', SiteStatsSettingsComponent::class);
        ModuleAdmin::registerSettings('sitestats', 'microweber-module-sitestats::settings');

    }

}
