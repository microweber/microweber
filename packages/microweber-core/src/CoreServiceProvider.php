<?php

namespace MicroweberPackages\Core;

use Illuminate\Support\ServiceProvider;

/**
 * CoreServiceProvider — deterministic, ordered loading of every Microweber
 * sub-package.
 *
 * Why this exists:
 *   Laravel's package auto-discovery reads `vendor/composer/installed.json`,
 *   builds `bootstrap/cache/packages.php` once and caches it.  Under php-fpm /
 *   php-cgi multiple concurrent requests can race while that cache file is
 *   being written (truncated read, partial write, opcache serving a stale
 *   version), which causes "Call to undefined function normalize_path()" and
 *   similar errors because the `files` autoload for
 *   `microweber-filesystem/src/helpers.php` has not been executed yet.
 *
 *   This provider registers every internal package provider in a fixed,
 *   deterministic order, and the `dont-discover: ["*"]` directive in the root
 *   `composer.json` tells Laravel to skip auto-discovery entirely.
 *
 *   In the Microweber monorepo it is registered as the very first statement of
 *   `AppServiceProvider::register()` (the app's single master provider, and the
 *   only entry in `bootstrap/providers.php`), so every package loads before the
 *   rest of the application registers.
 *
 * Usage in a standalone Laravel app:
 *   1. `composer require microweber-packages/core`
 *   2. Register `\MicroweberPackages\Core\CoreServiceProvider::class` early — as
 *      the first entry of `bootstrap/providers.php`, or from the top of your own
 *      `AppServiceProvider::register()`.
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Internal Microweber package providers, in dependency order.
     *
     * Lower-level packages (filesystem, helpers, config) come first so their
     * helper functions and singletons are available when higher-level packages
     * boot.
     *
     * Each entry is a fully-qualified class name.  The provider is only
     * registered if the class actually exists (allows the core package to be
     * used in lighter installations where not every sub-package is present).
     */
    protected array $packageProviders = [
        // ── Layer 0: Foundation helpers (functions, filesystem, format) ──
        \MicroweberPackages\Filesystem\FilesystemServiceProvider::class,
        \MicroweberPackages\Format\FormatServiceProvider::class,
        \MicroweberPackages\Security\SecurityServiceProvider::class,
        \MicroweberPackages\Http\HttpServiceProvider::class,

        // ── Layer 1: Config, caching, env ──
        \MicroweberPackages\TaggableFileCache\TaggableFileCacheServiceProvider::class,
        \MicroweberPackages\EnvWriter\EnvWriterServiceProvider::class,
        \MicroweberPackages\BladeCache\BladeCacheServiceProvider::class,

        // ── Layer 2: Database, repositories, search ──
        \MicroweberPackages\Repository\Providers\RepositoryServiceProvider::class,
        \MicroweberPackages\Searchable\SearchableServiceProvider::class,
        \MicroweberPackages\Url\Providers\UrlServiceProvider::class,

        // ── Layer 3: Media / thumbnailer ──
        \MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider::class,

        // ── Layer 4: Frontend assets ──
        \MicroweberPackages\FrontendAssets\MicroweberFrontendAssetsServiceProvider::class,
        \MicroweberPackages\FrontendAssetsLibs\MicroweberFrontendAssetsLibsServiceProvider::class,

        // ── Layer 5: PhpQuery ──
        \MicroweberPackages\PhpQuery\Providers\PhpQueryServiceProvider::class,
    ];

    /**
     * Third-party providers that were previously auto-discovered.
     *
     * Listed here so they load deterministically.  We still guard with
     * class_exists() in case the dependency is optional or was removed.
     */
    protected array $thirdPartyProviders = [
        \Carbon\Laravel\ServiceProvider::class,
        \Akaunting\Money\Provider::class,
        \Barryvdh\DomPDF\ServiceProvider::class,
        \BezhanSalleh\LanguageSwitch\LanguageSwitchServiceProvider::class,
        \BladeUI\Icons\BladeIconsServiceProvider::class,
        \BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
        \BobiMicroweber\FilamentFlatpickr\FilamentFlatpickrServiceProvider::class,
        \Coolsam\Flatpickr\FlatpickrServiceProvider::class,
        \Coolsam\Modules\ModulesServiceProvider::class,
        \DutchCodingCompany\FilamentSocialite\FilamentSocialiteServiceProvider::class,
        \EloquentFilter\ServiceProvider::class,
        \Filament\Support\SupportServiceProvider::class,
        \Filament\Actions\ActionsServiceProvider::class,
        \Filament\FilamentServiceProvider::class,
        \Filament\Forms\FormsServiceProvider::class,
        \Filament\Infolists\InfolistsServiceProvider::class,
        \Filament\Notifications\NotificationsServiceProvider::class,
        \Filament\QueryBuilder\QueryBuilderServiceProvider::class,
        \Filament\Schemas\SchemasServiceProvider::class,
        \Filament\Tables\TablesServiceProvider::class,
        \Filament\Widgets\WidgetsServiceProvider::class,
        \Flowframe\Trend\TrendServiceProvider::class,
        \Fruitcake\LaravelDebugbar\ServiceProvider::class,
        \GrahamCampbell\Markdown\MarkdownServiceProvider::class,
        \Hydrat\TableLayoutToggle\TableLayoutToggleServiceProvider::class,
        \Intervention\Image\Laravel\ServiceProvider::class,
        \JaOcero\RadioDeck\RadioDeckServiceProvider::class,
        \Jenssegers\Agent\AgentServiceProvider::class,
        \Jantinnerezo\LivewireRangeSlider\LivewireRangeSliderServiceProvider::class,
        \Kirschbaum\PowerJoins\PowerJoinsServiceProvider::class,
        \L5Swagger\L5SwaggerServiceProvider::class,
        \LaraZeus\Accordion\AccordionServiceProvider::class,
        \LaraZeus\SpatieTranslatable\SpatieTranslatableServiceProvider::class,
        \Laravel\Passkeys\PasskeysServiceProvider::class,
        \Laravel\Socialite\SocialiteServiceProvider::class,
        \Laravel\Tinker\TinkerServiceProvider::class,
        \Livewire\LivewireServiceProvider::class,
        \Mtrajano\LaravelSwagger\SwaggerServiceProvider::class,
        \NetTantra\FilamentSliderInputField\FilamentSliderInputFieldServiceProvider::class,
        \NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider::class,
        \RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider::class,
        \SolutionForest\FilamentTranslateField\FilamentTranslateFieldServiceProvider::class,
        \Spatie\GoogleTagManager\GoogleTagManagerServiceProvider::class,
        \Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
        \Spatie\Menu\Laravel\MenuServiceProvider::class,
        \Spatie\Permission\PermissionServiceProvider::class,
        \Spatie\Translatable\TranslatableServiceProvider::class,
        \Squire\CountriesServiceProvider::class,
        \Squire\CountriesEnServiceProvider::class,
        \Squire\CurrenciesServiceProvider::class,
        \Squire\CurrenciesEnServiceProvider::class,
        \Squire\ModelServiceProvider::class,
        \Squire\RepositoryServiceProvider::class,
        \Squire\TimezonesServiceProvider::class,
        \Squire\TimezonesEnServiceProvider::class,
        \Termwind\Laravel\TermwindServiceProvider::class,
        \Tightenco\Ziggy\ZiggyServiceProvider::class,
        \Arcanedev\SeoHelper\SeoHelperServiceProvider::class,
        \BobiMicroweber\FilamentDropdownColumn\FilamentDropdownColumnServiceProvider::class,
    ];

    /**
     * Dev / testing providers that were previously auto-discovered.
     *
     * These ship in `require-dev`, so their classes only exist in a dev or
     * testing install — the `class_exists()` guard skips them in production,
     * exactly reproducing Laravel's auto-discovery behaviour. Without these,
     * `php artisan dusk`, `dusk:chrome-driver` and `composer insights` would
     * silently lose their commands once auto-discovery is disabled.
     */
    protected array $devProviders = [
        \Laravel\Dusk\DuskServiceProvider::class,
        \Staudenmeir\DuskUpdater\DuskServiceProvider::class,
        \NunoMaduro\PhpInsights\Application\Adapters\Laravel\InsightsServiceProvider::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Microweber internal packages first (dependency order).
        foreach ($this->packageProviders as $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }

        // Register third-party providers that were previously auto-discovered.
        foreach ($this->thirdPartyProviders as $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }

        // Register dev/testing providers (only present in dev installs).
        foreach ($this->devProviders as $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    /**
     * Boot services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->environment('testing')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/tests.php');
        }
    }

    /**
     * Get the list of internal Microweber package providers.
     *
     * Useful for testing / introspection.
     */
    public function getPackageProviders(): array
    {
        return $this->packageProviders;
    }

    /**
     * Get the list of third-party providers.
     */
    public function getThirdPartyProviders(): array
    {
        return $this->thirdPartyProviders;
    }

    /**
     * Get the list of dev/testing providers.
     */
    public function getDevProviders(): array
    {
        return $this->devProviders;
    }
}