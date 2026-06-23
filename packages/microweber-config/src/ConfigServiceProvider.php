<?php

namespace MicroweberPackages\Config;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $config = new ConfigRepository($this->app);
        $this->app->instance('config', $config);

        // Clear the facade's cached instance so it picks up our new
        // ConfigRepository instead of the original Repository instance
        // that was resolved during bootstrap.
        Facade::clearResolvedInstance('config');
    }
}