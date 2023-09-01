<?php

namespace MicroweberPackages\Modules\Slider\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Slider\Http\Livewire\SliderSettingsComponent;

class SliderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-slider');
        $package->hasViews('microweber-module-slider');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-slider::settings', SliderSettingsComponent::class);

    }

}
