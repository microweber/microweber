<?php

namespace MicroweberPackages\Modules\Logo\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class LogoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-logo');
        $package->hasViews('microweber-module-logo');
    }


    public function register(): void
    {
        parent::register();

       // View::addNamespace('microweber-module-example-ui', __DIR__.'/../resources/views');
        Livewire::component('microweber-module-logo::settings', LogoSettingsComponent::class);

    }
}
