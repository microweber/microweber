<?php

namespace MicroweberPackages\MediaThumbnail;

use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class MediaThumbnailServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/media-thumbnail');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/media-thumbnail.php', 'media-thumbnail');

        $this->app->singleton(MediaThumbnailRepository::class, function () {
            return new MediaThumbnailRepository();
        });

    }

    public function packageBooted(): void
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