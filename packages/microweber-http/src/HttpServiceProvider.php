<?php

namespace MicroweberPackages\Http;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class HttpServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/http');
    }

    public function packageRegistered(): void
    {
        $this->app->bind(HttpService::class, function ($app) {
            return new HttpService($app);
        });
    }

    public function packageBooted(): void
    {
        //
    }
}
