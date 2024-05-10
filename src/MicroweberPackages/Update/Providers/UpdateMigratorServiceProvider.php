<?php

namespace MicroweberPackages\Update\Providers;


use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class UpdateMigratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-update');
        $package->hasMigration('2024_05_10_000001_migrate_old_version_213');

        $package->runsMigrations(true);

    }

    public function register(): void {

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

        parent::register();

    }

}
