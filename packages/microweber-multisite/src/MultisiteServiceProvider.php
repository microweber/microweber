<?php

declare(strict_types=1);

namespace MicroweberPackages\Multisite;

use MicroweberPackages\Multisite\Console\MultisiteInstallCommand;
use MicroweberPackages\Multisite\Console\MultisiteListCommand;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

/**
 * Registers the multisite manager and applies per-site isolation as early as
 * possible (during register(), before cache/session/filesystem services are
 * resolved). Depends on microweber-packages/env-writer for the install command,
 * which writes each domain's .env.{domain} credentials.
 */
class MultisiteServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('microweber-packages/multisite')
            ->hasConfigFile('multisite')
            ->hasCommands([
                MultisiteListCommand::class,
                MultisiteInstallCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(MultisiteManager::class, function ($app) {
            return new MultisiteManager((array) $app['config']->get('multisite', []));
        });
        $this->app->alias(MultisiteManager::class, 'multisite');

        if (!(bool) $this->app['config']->get('multisite.enabled', true)) {
            return;
        }

        // Create the per-site storage trees immediately (cheap, idempotent) so
        // the first request never races a missing cache/session dir.
        $this->safely(fn () => $this->app->make(MultisiteManager::class)->ensureDirectories());

        // Apply the cache/session config overrides in a booted() callback, NOT
        // here. Microweber's ConfigServiceProvider swaps Laravel's config
        // repository for its own during the register phase; anything written to
        // config() before that swap is discarded. booted() runs after every
        // provider has registered and booted — so the overrides land on the
        // final repository and still beat the lazy resolution of the cache and
        // session managers (which happens during request handling).
        $this->app->booted(function () {
            $this->safely(fn () => $this->app->make(MultisiteManager::class)->applyIsolation());
        });
    }

    /** Run a boot-time step without ever letting it abort application boot. */
    protected function safely(callable $fn): void
    {
        try {
            $fn();
        } catch (\Throwable $e) {
            // single-site boot must never fail because of multisite
        }
    }
}
