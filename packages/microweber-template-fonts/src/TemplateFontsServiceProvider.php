<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

class TemplateFontsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/template-fonts.php', 'template-fonts');

        $this->app->singleton(TemplateFontsManager::class, function ($app) {
            $config = (array) config('template-fonts', []);

            // When running inside Microweber CMS, prefer userfiles paths
            if (function_exists('userfiles_path')) {
                $config['fonts_path'] = rtrim(userfiles_path(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'fonts';
            }
            if (function_exists('userfiles_url')) {
                $config['fonts_url'] = rtrim(userfiles_url(), '/') . '/fonts';
            }
            if (function_exists('userfiles_path')) {
                $config['css_cache_path'] = rtrim(userfiles_path(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'cache';
            }
            if (function_exists('userfiles_url')) {
                $config['css_cache_url'] = rtrim(userfiles_url(), '/') . '/cache';
            }
            if (config('microweber.compile_assets') !== null) {
                $config['compile_assets'] = (bool) config('microweber.compile_assets');
            }

            return new TemplateFontsManager($config);
        });

    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'template-fonts');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/template-fonts.php' => config_path('template-fonts.php'),
            ], 'template-fonts-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'template-fonts-migrations');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/template-fonts'),
            ], 'template-fonts-views');
        }
    }
}
