<?php

namespace MicroweberPackages\DbMigrator;

use Illuminate\Support\ServiceProvider;

class DbMigratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('mw_migrator', function ($app) {
            $repository = $app['migration.repository'];
            return new MicroweberMigrator($repository, $app['db'], $app['files'], $app['events']);
        });
    }

    public function boot(): void
    {
        //
    }
}