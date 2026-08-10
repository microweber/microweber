<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use MicroweberPackages\DbExport\Commands\DbExportCommand;
use MicroweberPackages\DbExport\Commands\DbImportCommand;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class DbExportServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/db-export');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(DbExportManager::class, function () {
            return new DbExportManager();
        });
    }

    public function packageBooted(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DbExportCommand::class,
                DbImportCommand::class,
            ]);
        }
    }
}