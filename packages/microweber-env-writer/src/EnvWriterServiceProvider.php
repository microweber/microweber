<?php

namespace MicroweberPackages\EnvWriter;

use Illuminate\Support\ServiceProvider;

class EnvWriterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EnvWriter::class, function () {
            return new EnvWriter();
        });

        $this->app->alias(EnvWriter::class, 'env-writer');
    }
}