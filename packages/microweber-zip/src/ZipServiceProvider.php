<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip;

use MicroweberPackages\Zip\Support\ZipBombGuard;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class ZipServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/zip');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/zip.php', 'zip');

        $this->app->singleton(ZipBombGuard::class, function ($app): ZipBombGuard {
            $raw = $app['config']->get('zip', []);
            /** @var array<string, int|float|string|null> $config */
            $config = is_array($raw) ? $raw : [];

            return ZipBombGuard::fromConfig($config);
        });

        $this->app->bind(Unzip::class, function ($app): Unzip {
            return new Unzip($app->make(ZipBombGuard::class));
        });

        $this->app->bind(Zip::class, function (): Zip {
            return new Zip();
        });
    }

    public function packageBooted(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/zip.php' => config_path('zip.php'),
            ], 'zip-config');
        }
    }
}
