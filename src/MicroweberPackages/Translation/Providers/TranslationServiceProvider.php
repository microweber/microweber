<?php

namespace MicroweberPackages\Translation\Providers;

use Illuminate\Support\Facades\Schema;

class TranslationServiceProvider extends \Spatie\TranslationLoader\TranslationServiceProvider
{


    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (mw_is_installed()) {
            if (!Schema::hasTable('language_lines')) {
                app()->mw_migrator->run([
                    dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations'
                ]);
            }
        }
    }

    protected function registerLoader()
    {
        if (mw_is_installed()) {
            $this->app->singleton('translation.loader', function ($app) {
                $class = config('translation-loader.translation_manager');
                return new $class($app['files'], $app['path.lang']);
            });
        }
    }
}
