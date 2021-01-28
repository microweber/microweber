<?php

namespace MicroweberPackages\Translation\Providers;

use Illuminate\Support\Str;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;
use MicroweberPackages\Translation\TranslationManager;

class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{
    /**
     * Register the translation line loader. This method registers a
     * `TranslationLoaderManager` instead of a simple `FileLoader` as the
     * applications `translation.loader` instance.
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationManager($app['files'], $app['path.lang']);
        });
    }
}
