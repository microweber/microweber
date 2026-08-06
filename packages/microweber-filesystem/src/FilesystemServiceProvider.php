<?php

namespace MicroweberPackages\Filesystem;

use Illuminate\Support\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FilesystemService::class, function () {
            return new FilesystemService();
        });

        $this->app->alias(FilesystemService::class, 'mw_filesystem');
    }

    public function boot(): void
    {
        //
    }
}