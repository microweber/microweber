<?php

namespace MicroweberPackages\Modules\Categories\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Categories\Http\Livewire\CategorySettingsComponent;

class CategoryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-category');
        $package->hasViews('microweber-module-category');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-category::settings', CategorySettingsComponent::class);
        ModuleAdmin::registerSettings('categories', 'microweber-module-category::settings');

    }

}
