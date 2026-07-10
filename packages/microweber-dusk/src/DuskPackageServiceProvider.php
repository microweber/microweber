<?php

namespace MicroweberPackages\Dusk;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class DuskPackageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerBrowserMacros();
    }

    public function boot(): void
    {
        //
    }

    /**
     * Register the shared Dusk Browser macros (iframe switching, with optional
     * coverage capture). Moved here from the former
     * MicroweberPackages\Dusk\DuskServiceProvider so all Browser macros live in
     * this package. Consumed by the legacy browser suites (tests/BrowserLegacy)
     * and available to any Dusk test.
     */
    protected function registerBrowserMacros(): void
    {
        // laravel/dusk is a dev-only dependency; skip cleanly if absent.
        if (!class_exists(Browser::class)) {
            return;
        }

        Browser::macro('switchFrame', function ($frame) {
            $this->saveDuskCoverage();
            $this->driver->switchTo()->defaultContent()->switchTo()->frame($frame);

            return $this;
        });

        Browser::macro('switchFrameDefault', function () {
            $this->saveDuskCoverage();
            $this->driver->switchTo()->defaultContent()->switchTo()->defaultContent();

            return $this;
        });

        // Capture JS coverage (window.__coverage__) into the package's coverage
        // sink. DuskCoverage::save() is a no-op on empty coverage, so this is safe
        // to call unconditionally.
        Browser::macro('saveDuskCoverage', function () {
            DuskCoverage::save($this->driver->executeScript('return window.__coverage__'));

            return $this;
        });
    }
}
