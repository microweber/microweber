<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use MicroweberPackages\Passport\Services\RSAKeyManager;

/**
 * Microweber Passport service provider.
 *
 * Registers Laravel Passport, generates RSA keys on the fly when
 * missing, publishes custom migrations that add Microweber-specific
 * columns (last_used_at, last_used_ip) to the Passport tables, and
 * wires up the Filament admin page for API key management when
 * running inside Microweber CMS.
 *
 * Works standalone in any Laravel 11+ app — no Microweber CMS required.
 */
class MicroweberPassportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/passport.php', 'microweber-passport');

        // Register Passport itself
        $this->app->register(\Laravel\Passport\PassportServiceProvider::class);

        // Load the /api/passport/* routes in the REGISTER phase so they register
        // before a host app's boot()-time catch-all (e.g. Microweber's greedy
        // api/{all} route). Route middleware aliases resolve at request time, so
        // registering routes here (before registerMiddleware() in boot()) is fine.
        $this->registerRoutes();

        // Register the Filament admin page in the REGISTER phase too, so it lands
        // in the FilamentRegistry BEFORE the admin panel reads it to build its
        // routes (the panel is assembled during the boot phase). Registering it in
        // boot() would be too late now that this provider boots after the panel.
        $this->registerFilament();
    }

    public function boot(): void
    {
        $this->ensureRSAKeys();
        $this->configurePassport();
        $this->registerMigrations();
        $this->registerMiddleware();
        $this->registerViews();
        $this->publishAssets();
    }

    protected function ensureRSAKeys(): void
    {
        RSAKeyManager::ensureKeys(storage_path());
    }

    protected function configurePassport(): void
    {
        $config = $this->app['config'];

        Passport::tokensExpireIn(
            now()->addDays((int) $config->get('microweber-passport.tokens_expire_days', 15))
        );
        Passport::refreshTokensExpireIn(
            now()->addDays((int) $config->get('microweber-passport.refresh_tokens_expire_days', 30))
        );
        Passport::personalAccessTokensExpireIn(
            now()->addDays((int) $config->get('microweber-passport.personal_access_tokens_expire_days', 365))
        );

        $scopes = $config->get('microweber-passport.scopes', []);
        if (!empty($scopes)) {
            Passport::tokensCan($scopes);
        }

        Passport::setDefaultScope(
            (array) $config->get('microweber-passport.default_scope', ['*'])
        );

        // The personal-access client (Passport 12+ no longer auto-creates one) is
        // provisioned by the 2025_01_01_000002_create_personal_access_client
        // migration — created once, not checked on every request.
    }

    protected function registerMigrations(): void
    {
        // Load Passport's own migrations
        $this->loadMigrationsFrom(
            dirname((new \ReflectionClass(Passport::class))->getFileName(), 2) . '/database/migrations'
        );

        // Load our custom Microweber additions (extra columns, etc.)
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
    }

    protected function registerMiddleware(): void
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->aliasMiddleware('scope', \Laravel\Passport\Http\Middleware\CheckToken::class);
        $router->aliasMiddleware('scopes', \Laravel\Passport\Http\Middleware\CheckTokenForAnyScope::class);
        $router->aliasMiddleware('token.audit', \MicroweberPackages\Passport\Http\Middleware\StampTokenLastUsed::class);
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'microweber-passport');
    }

    protected function registerFilament(): void
    {
        // Only register Filament pages when the CMS's FilamentRegistry is
        // available — this keeps the package usable in plain Laravel apps.
        if (class_exists(\MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry::class)) {
            \MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry::registerPage(
                \MicroweberPackages\Passport\Filament\Pages\ApiApplicationsPage::class
            );
        }
    }

    protected function publishAssets(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/passport.php' => config_path('microweber-passport.php'),
            ], 'microweber-passport-config');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'microweber-passport-migrations');
        }
    }
}