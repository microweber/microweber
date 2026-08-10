<?php

namespace MicroweberPackages\Thumbnailer;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class ThumbnailerServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/thumbnailer');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/thumbnailer.php', 'thumbnailer');

        $this->app->singleton(ThumbnailGenerator::class, function ($app) {
            return new ThumbnailGenerator(
                config('thumbnailer.thumbnails_path', storage_path('app/public/thumbnails')),
                config('thumbnailer.thumbnails_url', '/storage/thumbnails')
            );
        });

    }

    public function packageBooted(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/thumbnailer.php' => config_path('thumbnailer.php'),
            ], 'thumbnailer-config');
        }
    }
}