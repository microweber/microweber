<?php

namespace MicroweberPackages\PhpQuery\Providers;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class PhpQueryServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/phpquery');
    }

    /**
     * Register the service provider.
     *
     * phpQuery is used directly via the static {@see \MicroweberPackages\PhpQuery\PhpQuery}
     * class (and the pq() helper) — there is no manager/facade indirection.
     */
    public function packageRegistered()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function packageBooted()
    {
        //
    }
}