<?php
namespace MicroweberPackages\Modules\Shop\Coupons\Providers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ShopCouponServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-shop-coupons');
        $package->hasRoute('api');
        $package->hasMigration('2023_00_00_000000_create_cart_coupons_table');
        $package->hasMigration('2023_00_00_000001_create_cart_coupons_log_table');
        $package->runsMigrations(true);
    }



}
