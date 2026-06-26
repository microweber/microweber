<?php

namespace MicroweberPackages\DbInstaller;

use Illuminate\Support\ServiceProvider;

class DbInstallerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DbInstaller::class, function () {
            return new DbInstaller();
        });
    }

    public function boot(): void
    {
        //
    }
}
