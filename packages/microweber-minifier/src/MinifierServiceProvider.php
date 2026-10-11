<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier;

use MicroweberPackages\Minifier\Services\CssMinify;
use MicroweberPackages\Minifier\Services\JsMinify;
use MicroweberPackages\Minifier\Services\MinifierService;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class MinifierServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/minifier');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/minifier.php', 'minifier');

        $this->app->singleton(JsMinify::class, static fn () => new JsMinify());
        $this->app->singleton(CssMinify::class, static fn () => new CssMinify());

        $this->app->singleton(MinifierService::class, function ($app) {
            return new MinifierService(
                $app->make(JsMinify::class),
                $app->make(CssMinify::class),
            );
        });

    }

    /**
     * Load the Asset Minifier settings saved on the System Settings page
     * (options group "minifier") into runtime config so the toggles actually
     * take effect across requests. Without this, config('minifier.*') only ever
     * reflects the static config/env defaults and the page's Save does nothing
     * beyond the request it ran in.
     */
    protected function loadPersistedSettings(): void
    {
        // Skip CLI (except the test runner) so migrations / package:discover
        // never hit the options table before it exists.
        if ($this->app->runningInConsole() && !$this->app->runningUnitTests()) {
            return;
        }

        if (!function_exists('get_option')) {
            return;
        }

        $map = [
            'minifier_enabled' => 'minifier.enabled',
            'minifier_minify_js' => 'minifier.minify_js',
            'minifier_minify_css' => 'minifier.minify_css',
            'minifier_js_flagged_comments' => 'minifier.js.flaggedComments',
        ];

        try {
            foreach ($map as $optionKey => $configKey) {
                $value = get_option($optionKey, 'minifier');
                $bool = \MicroweberPackages\Minifier\Filament\Pages\MinifierSettings::optionToBool($value);
                // Only override the default when the option explicitly stored a
                // boolean token; an unset option (null) keeps the config default.
                if ($bool !== null && $value !== null && $value !== '') {
                    config([$configKey => $bool]);
                }
            }
        } catch (\Throwable) {
            // Keep the static config defaults if options are unavailable.
        }
    }

    public function packageBooted(): void
    {
        $this->loadPersistedSettings();

        // The HTTP routes exist only so the test suite can exercise
        // MinifierController end-to-end. The CMS minifies via the CssMinify /
        // JsMinify services directly (see AssetOptimizationService), so there is
        // no reason to expose these endpoints in a normal environment — register
        // them only when running the tests.
        if ($this->app->runningUnitTests() || $this->app->environment('testing')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'minifier');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/minifier.php' => config_path('minifier.php'),
            ], 'minifier-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/minifier'),
            ], 'minifier-views');
        }
    }
}
