<?php

namespace MicroweberPackages\Thumbnailer;

use Illuminate\Support\ServiceProvider;

class ThumbnailerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/thumbnailer.php', 'thumbnailer');

        $this->app->singleton(ThumbnailGenerator::class, function ($app) {
            return new ThumbnailGenerator(
                config('thumbnailer.thumbnails_path', storage_path('app/public/thumbnails')),
                config('thumbnailer.thumbnails_url', '/storage/thumbnails')
            );
        });

        $this->app->alias(ThumbnailGenerator::class, 'thumbnailer');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/thumbnailer.php' => config_path('thumbnailer.php'),
            ], 'thumbnailer-config');
        }
    }
}