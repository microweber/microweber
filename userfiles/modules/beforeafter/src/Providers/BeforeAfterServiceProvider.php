<?php

namespace MicroweberPackages\Modules\BeforeAfter\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\BeforeAfter\Http\Livewire\BeforeAfterSettingsComponent;

class BeforeAfterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-before-after');
        $package->hasViews('microweber-module-before-after');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-before-after::settings', BeforeAfterSettingsComponent::class);

    }

}
