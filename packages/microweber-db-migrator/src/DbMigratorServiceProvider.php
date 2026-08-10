<?php

namespace MicroweberPackages\DbMigrator;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class DbMigratorServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/db-migrator');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('mw_migrator', function ($app) {
            $repository = $app['migration.repository'];
            return new MicroweberMigrator($repository, $app['db'], $app['files'], $app['events']);
        });
    }

    public function packageBooted(): void
    {
        //
    }
}