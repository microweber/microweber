<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\DbExport\Commands\DbExportCommand;
use MicroweberPackages\DbExport\Commands\DbImportCommand;

class DbExportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DbExportManager::class, function () {
            return new DbExportManager();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DbExportCommand::class,
                DbImportCommand::class,
            ]);
        }
    }
}