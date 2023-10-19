<?php

namespace MicroweberPackages\Modules\Shop\Products\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Posts\Http\Livewire\ProductsSettingsComponent;

class ProductsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-shop-products');
        $package->hasViews('microweber-module-shop-products');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-shop-products::settings', ProductsSettingsComponent::class);
        ModuleAdmin::registerSettings('shop/products', 'microweber-module-shop-products::settings');
    }

}
