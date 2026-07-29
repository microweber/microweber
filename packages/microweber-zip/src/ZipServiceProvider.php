<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Zip\Support\ZipBombGuard;

class ZipServiceProvider extends ServiceProvider
{
    public function register(): void
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

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/zip.php' => config_path('zip.php'),
            ], 'zip-config');
        }
    }
}
