<?php

namespace MicroweberPackages\Modules\Shop\Providers;

use Livewire\Livewire;
use MicroweberPackages\Modules\Shop\Http\Livewire\ShopComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Shop\Http\Livewire\ShopSettingsComponent;

class ShopServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-shop');
        $package->hasViews('microweber-module-shop');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-shop::index', ShopComponent::class);


       // Livewire::component('microweber-module-shop::settings', ShopSettingsComponent::class);
//        ModuleAdmin::registerSettings('shop', 'microweber-module-shop::settings');

    }

}
