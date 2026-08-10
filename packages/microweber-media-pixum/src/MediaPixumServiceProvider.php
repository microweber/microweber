<?php

namespace MicroweberPackages\MediaPixum;

use Illuminate\Support\ServiceProvider;

class MediaPixumServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/media-pixum.php', 'media-pixum');

        $this->app->singleton(PixumGenerator::class, function ($app) {
            $config = $app['config']['media-pixum'];

            return new PixumGenerator(
                cachePath: $config['cache_path'] ?? storage_path('app/public/pixum'),
                bgColor: $config['background_color'] ?? ['r' => 239, 'g' => 236, 'b' => 236, 'a' => 0],
                maxWidth: $config['max_width'] ?? 4000,
                maxHeight: $config['max_height'] ?? 4000
            );
        });

    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/media-pixum.php' => config_path('media-pixum.php'),
            ], 'media-pixum-config');
        }
    }
}