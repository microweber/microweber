<?php

namespace MicroweberPackages\Modules\Categories\CategoryImages\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Categories\CategoryImages\Http\Livewire\CategoryImagesSettingsComponent;

class CategoryImagesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-category-images');
        $package->hasViews('microweber-module-category-images');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-category-images::settings', CategoryImagesSettingsComponent::class);
        ModuleAdmin::registerSettings('categories/category_images', 'microweber-module-category-images::settings');

    }

}
