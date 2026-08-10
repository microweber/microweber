<?php

namespace MicroweberPackages\Filesystem;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class FilesystemServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/filesystem');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FilesystemService::class, function () {
            return new FilesystemService();
        });

    }

    public function packageBooted(): void
    {
        //
    }
}