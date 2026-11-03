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
        // NB: do NOT $package->hasViews() here. spatie resolves its views dir as
        // <packageRoot>/resources/views (i.e. src/../resources/views), which this
        // package does not have — that non-existent hint path made `view:cache`
        // throw. The views actually live in src/resources/views and are
        // registered under the same namespace by prependNamespace() in boot().
    }
    public function boot(): void
    {
        parent::boot();

        // Register the real views dir (src/resources/views) for the namespace.
        View::prependNamespace('microweber-ui', dirname(__DIR__).'/resources/views');
//        Blade::componentNamespace('MicroweberPackages\\View\\Views\\Components', 'mw-ui');
//        Blade::component('tabs', Tabs::class);


    }
}
