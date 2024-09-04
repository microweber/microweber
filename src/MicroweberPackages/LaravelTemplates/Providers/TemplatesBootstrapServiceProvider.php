<?php

namespace MicroweberPackages\LaravelTemplates\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelTemplates\Contracts\TemplatesRepositoryInterface;

class TemplatesBootstrapServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        $this->app[TemplatesRepositoryInterface::class]->boot();
    }

    /**
     * Register the provider.
     */
    public function register(): void
    {
        $this->app[TemplatesRepositoryInterface::class]->register();
    }
}
