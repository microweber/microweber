<?php

namespace MicroweberPackages\Modules\Tabs\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Tabs\Http\Livewire\TabsSettingsComponent;

class TabsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-tabs');
        $package->hasViews('microweber-module-tabs');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-tabs::settings', TabsSettingsComponent::class);

    }

}
