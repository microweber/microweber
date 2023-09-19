<?php

namespace MicroweberPackages\Modules\BeforeAfter\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\BeforeAfter\Http\Livewire\BeforeAfterSettingsComponent;

class BeforeAfterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-beforeafter');
        $package->hasViews('microweber-module-beforeafter');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-beforeafter::settings', BeforeAfterSettingsComponent::class);

        ModuleAdmin::registerSettings('beforeafter', 'microweber-module-beforeafter::settings');
    }

}
