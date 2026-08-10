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

    }

    public function boot(): void
    {
        //
    }
}