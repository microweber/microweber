<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NewsletterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-newsletter');
        $package->hasViews('microweber-module-newsletter');
    }


    public function register(): void
    {
        parent::register();


    }
}
