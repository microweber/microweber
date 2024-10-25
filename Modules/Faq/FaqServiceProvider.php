<?php

namespace Modules\Faq;

use Illuminate\Support\ServiceProvider;

class FaqServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the Faq module
        $this->app->singleton(Faq::class, function ($app) {
            return new Faq();
        });

        // Register the Filament module
        $this->app->singleton(Microweber\FaqModule::class, function ($app) {
            return new Microweber\FaqModule();
        });
    }

    public function boot()
    {
        // Bootstrapping code here
    }
}

use Illuminate\Support\ServiceProvider;

class FaqServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the FAQ module
    }

    public function boot()
    {
        // Boot the FAQ module
    }
}

use Illuminate\Support\ServiceProvider;

class FaqServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register your module here
    }

    public function boot()
    {
        // Boot your module here
    }
}

use Illuminate\Support\ServiceProvider;
use Modules\Faq\Filament\FaqModuleSettings;
use Modules\Faq\Microweber\FaqModule;

class FaqServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the module settings
        $this->app->singleton(FaqModuleSettings::class, function ($app) {
            return new FaqModuleSettings();
        });

        // Register the module
        $this->app->singleton(FaqModule::class, function ($app) {
            return new FaqModule();
        });
    }

    public function boot()
    {
        // Bootstrapping logic if needed
    }
}
