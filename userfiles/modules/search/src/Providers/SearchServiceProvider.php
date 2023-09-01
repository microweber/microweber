<?php

namespace MicroweberPackages\Modules\Search\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Search\Http\Livewire\SearchSettingsComponent;

class SearchServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-search');
        $package->hasViews('microweber-module-search');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-search::settings', SearchSettingsComponent::class);

    }

}
