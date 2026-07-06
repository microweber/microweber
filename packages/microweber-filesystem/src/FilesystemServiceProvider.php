<?php

namespace MicroweberPackages\Filesystem;

use Illuminate\Support\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('mw_filesystem', function () {
            return new FilesystemService();
        });

        $this->app->singleton(FilesystemService::class, function ($app) {
            return $app->make('mw_filesystem');
        });
    }

    public function boot(): void
    {
        //
    }
}