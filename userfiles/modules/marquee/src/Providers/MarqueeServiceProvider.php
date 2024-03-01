<?php

namespace MicroweberPackages\Modules\Marquee\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Marquee\Http\Livewire\MarqueeSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class MarqueeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-marquee');
        $package->hasViews('microweber-module-marquee');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-marquee::settings', MarqueeSettingsComponent::class);

        ModuleAdmin::registerSettings('marquee', 'microweber-module-marquee::settings');
    }
}
