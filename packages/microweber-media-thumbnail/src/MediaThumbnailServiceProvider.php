<?php

namespace MicroweberPackages\MediaThumbnail;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;

class MediaThumbnailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/media-thumbnail.php', 'media-thumbnail');

        $this->app->singleton(MediaThumbnailRepository::class, function () {
            return new MediaThumbnailRepository();
        });

        $this->app->alias(MediaThumbnailRepository::class, 'media-thumbnail');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/media-thumbnail.php' => config_path('media-thumbnail.php'),
            ], 'media-thumbnail-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'media-thumbnail-migrations');
        }
    }
}