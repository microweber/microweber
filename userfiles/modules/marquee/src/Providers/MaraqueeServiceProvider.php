<?php

namespace MicroweberPackages\Modules\Maraquee\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Logo\Http\Livewire\MaraqueeSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class MaraqueeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-maraquee');
        $package->hasViews('microweber-module-maraquee');
    }


    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-maraquee::settings', MaraqueeSettingsComponent::class);
        ModuleAdmin::registerSettings('maraquee', 'microweber-module-maraquee::settings');

    }
}
