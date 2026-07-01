<?php

declare(strict_types=1);

namespace Modules\StaticPageCache\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use Modules\StaticPageCache\Filament\StaticPageCacheSettingsPage;
use Modules\StaticPageCache\Http\Middleware\StaticPageCacheMiddleware;
use Modules\StaticPageCache\Services\StaticPageCacheService;

class StaticPageCacheServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'StaticPageCache';

    protected string $moduleNameLower = 'static-page-cache';

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'static-page-cache');

        $this->app->singleton(StaticPageCacheService::class, function () {
            return new StaticPageCacheService();
        });

        $this->registerConfig();
        $this->registerViews();
        $this->registerTranslations();

        // Register the settings page in the admin panel. This must run in register()
        // (like the Settings module) — by boot() the admin panel has already collected
        // its pages, so a late registerPage() would never create a route.
        if (class_exists(FilamentRegistry::class)) {
            try {
                FilamentRegistry::registerPage(StaticPageCacheSettingsPage::class);
            } catch (\Exception $e) {
                // FilamentRegistry may not be available yet
            }
        }
    }

    /**
     * Models whose saves/deletes affect rendered page output and should therefore
     * invalidate the static page cache. Scoped deliberately (instead of a
     * `eloquent.saved: *` wildcard) so unrelated saves don't flush the whole
     * page cache on every model write.
     */
    protected array $cacheInvalidatingModels = [
        \Modules\Content\Models\Content::class,
        \Modules\Page\Models\Page::class,
        \Modules\Post\Models\Post::class,
        \Modules\Product\Models\Product::class,
        \Modules\Category\Models\Category::class,
        \Modules\Menu\Models\Menu::class,
    ];

    public function boot(): void
    {
        // Clear the static page cache only when a content-affecting model is saved
        // or deleted. The eloquent.* event name carries the concrete class, so each
        // model is registered explicitly (a Product save fires `eloquent.saved: …\Product`,
        // not its Content parent).
        $clear = function () {
            try {
                app(StaticPageCacheService::class)->clear();
            } catch (\Exception $e) {
                // Silently fail – cache clear is best-effort
            }
        };

        foreach ($this->cacheInvalidatingModels as $model) {
            $this->app['events']->listen('eloquent.saved: ' . $model, $clear);
            $this->app['events']->listen('eloquent.deleted: ' . $model, $clear);
        }
    }
}