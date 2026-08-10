<?php

namespace MicroweberPackages\MicroweberUI\Providers;

use Illuminate\Support\Facades\View;
use Spatie\LaravelPackageTools\Package;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;

class MicroweberUIServiceProvider extends MicroweberPackageServiceProvider
{

     public function configurePackage(Package $package): void
    {
        $package->name('microweber-ui');
        $package->hasViews('microweber-ui');
    }
    public function boot()
    {
        parent::boot();

        View::prependNamespace('microweber-ui', dirname(__DIR__).'/resources/views');
//        Blade::componentNamespace('MicroweberPackages\\View\\Views\\Components', 'mw-ui');
//        Blade::component('tabs', Tabs::class);


    }
}
