<?php

namespace MicroweberPackages\MicroweberUI\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use MicroweberPackages\MicroweberUI\Components\Tabs;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MicroweberUIServiceProvider extends PackageServiceProvider
{

     public function configurePackage(Package $package): void
    {
        $package->name('microweber-ui');
        $package->hasViews('microweber-ui');
    }
//    public function boot()
//    {
//        View::prependNamespace('microweber-ui', __DIR__.'/resources/views');
//        Blade::componentNamespace('MicroweberPackages\\View\\Views\\Components', 'mw-ui');
//
//        Blade::component('tabs', Tabs::class);
//
//    }
}
