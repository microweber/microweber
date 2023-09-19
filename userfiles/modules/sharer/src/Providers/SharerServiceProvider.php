<?php

namespace MicroweberPackages\Modules\Sharer\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Sharer\Http\Livewire\SharerSettingsComponent;

class SharerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-sharer');
        $package->hasViews('microweber-module-sharer');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-sharer::settings', SharerSettingsComponent::class);

        ModuleAdmin::registerSettings('sharer', 'microweber-module-sharer::settings');

    }

}
