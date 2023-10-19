<?php

namespace MicroweberPackages\Modules\Shop\Products\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Shop\Products\Http\Livewire\ProductsSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
