<?php

namespace MicroweberPackages\DbInstaller;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class DbInstallerServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/db-installer');
    }

    public function packageRegistered(): void
    {
        $this->app->bind(DbInstaller::class, function () {
            return new DbInstaller();
        });
    }

    public function packageBooted(): void
    {
        //
    }
}
