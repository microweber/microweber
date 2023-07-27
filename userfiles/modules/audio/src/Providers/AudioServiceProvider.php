<?php

namespace MicroweberPackages\Modules\Audio\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Audio\Http\Livewire\AudioSettingsComponent;

class AudioServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-audio');
        $package->hasViews('microweber-module-audio');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-audio::settings', AudioSettingsComponent::class);

    }

}
