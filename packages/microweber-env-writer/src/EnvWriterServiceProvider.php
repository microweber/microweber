<?php

namespace MicroweberPackages\EnvWriter;

use Illuminate\Support\ServiceProvider;

class EnvWriterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EnvWriterService::class, function () {
            return new EnvWriterService();
        });
    }
}
