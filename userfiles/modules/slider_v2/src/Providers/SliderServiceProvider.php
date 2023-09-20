<?php

namespace MicroweberPackages\Modules\SliderV2\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\SliderV2\Http\Livewire\SliderSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SliderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-slider-v2');
        $package->hasViews('microweber-module-slider-v2');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-slider-v2::settings', SliderSettingsComponent::class);
        ModuleAdmin::registerSettings('slider_v2', 'microweber-module-slider-v2::settings');

    }

}
