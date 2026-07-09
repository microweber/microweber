<?php

declare(strict_types=1);

namespace MicroweberPackages\ConfigMerge;

use Illuminate\Support\Arr;

/**
 * Trait MergesConfigFromPackage
 *
 * Provides package-priority config merging for Laravel service providers.
 *
 * Laravel's built-in mergeConfigFrom() gives the application config priority
 * over the package defaults (app wins). This trait inverts that behaviour so
 * that the package/module config values take priority and can override the
 * application-level defaults.
 *
 * This is useful when a CMS module or package ships opinionated defaults that
 * should "win" unless the system explicitly provides a value.
 *
 * Usage:
 *   use MicroweberPackages\ConfigMerge\MergesConfigFromPackage;
 *
 *   class MyServiceProvider extends ServiceProvider
 *   {
 *       use MergesConfigFromPackage;
 *
 *       public function register()
 *       {
 *           $this->mergeConfigFrom(__DIR__.'/../config/my.php', 'my');
 *       }
 *   }
 */
trait MergesConfigFromPackage
{
    /**
     * Recursively merge two config arrays with the $merging (package) values
     * taking priority over $original (application) values.
     *
     * Numeric-keyed arrays and special keys ('middleware', 'register') are
     * concatenated via array_merge instead of recursed into, preserving
     * list-append semantics.
     */
    protected function mergeConfig(array $original, array $merging): array
    {
        $array = array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            if (!Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            if ($key === 'middleware' || $key === 'register') {
                continue;
            }

            if (is_array($merging[$key])) {
                $array[$key] = $this->mergeConfig($value, $merging[$key]);
            }
            // If $merging[$key] is not an array (e.g. scalar overriding an array),
            // array_merge already placed the scalar in $array[$key] — keep it.
        }

        return $array;
    }

    /**
     * Merge the given configuration file with package-priority semantics.
     *
     * The values from $path (the package config) override the values already
     * present in the application config repository for the given $key.
     *
     * A missing $path is a no-op (the existing config is left untouched) rather
     * than a fatal `require` error — so a deleted/mistyped package config file
     * degrades gracefully instead of white-screening the whole app.
     */
    protected function mergeConfigFrom($path, $key): void
    {
        if (!($this->app instanceof \Illuminate\Contracts\Foundation\CachesConfiguration
            && $this->app->configurationIsCached())) {
            if (!is_file($path)) {
                return;
            }

            $config = $this->app['config']->get($key) ?? [];

            $packageConfig = require $path;

            if (!is_array($packageConfig)) {
                $packageConfig = [];
            }

            $this->app['config']->set($key, $this->mergeConfig($config, $packageConfig));
        }
    }
}