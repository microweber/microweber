<?php

namespace MicroweberPackages\ContentField\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\ContentField\TranslateTables\TranslateContentField;

class ContentFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->translate_manager->addTranslateProvider(TranslateContentField::class);
    }
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations/');

    }

}
