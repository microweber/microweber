<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader;

use Illuminate\Support\ServiceProvider;

class ClassLoaderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/class-loader.php', 'class-loader');

        // Honour an instance already bound early (e.g. AppServiceProvider constructor).
        if ($this->app->bound(ClassLoaderService::class)) {
            return;
        }

        $this->app->singleton(ClassLoaderService::class, function (): ClassLoaderService {
            $cache = (bool) config('class-loader.cache_lookups', true);
            $loader = new ClassLoaderService(new PathNormalizer(), $cache);

            if (!(bool) config('class-loader.enabled', true)) {
                return $loader;
            }

            $directories = config('class-loader.directories', []);
            if (is_array($directories) && $directories !== []) {
                $normalizedDirs = [];
                foreach ($directories as $directory) {
                    if (is_string($directory) && $directory !== '') {
                        $normalizedDirs[] = $directory;
                    }
                }
                if ($normalizedDirs !== []) {
                    $loader->addDirectories($normalizedDirs);
                }
            }

            $namespaces = config('class-loader.namespaces', []);
            if (is_array($namespaces)) {
                foreach ($namespaces as $namespace => $path) {
                    if (is_string($namespace) && is_string($path) && $path !== '') {
                        $loader->addNamespace($namespace, $path);
                    }
                }
            }

            return $loader;
        });

        // Activate the autoloader during the REGISTER phase, not boot: other
        // service providers may resolve autoloaded classes while they are still
        // registering (boot runs only after every register() has completed), so
        // registering the spl_autoload handler in boot() would be too late.
        if ((bool) config('class-loader.enabled', true)) {
            $this->app->make(ClassLoaderService::class)->register();
        }
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/class-loader.php' => config_path('class-loader.php'),
            ], 'class-loader-config');
        }

        // Free lookup caches on terminate so long-running workers / test suites
        // do not retain unbounded class-path maps.
        $this->app->terminating(function (): void {
            if ($this->app->resolved(ClassLoaderService::class)) {
                /** @var ClassLoaderService $loader */
                $loader = $this->app->make(ClassLoaderService::class);
                $loader->clearCache();
            }
        });
    }
}
