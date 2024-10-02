<?php

namespace Modules\Sitemap\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

class SitemapServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Sitemap';

    protected string $moduleNameLower = 'sitemap';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        Route::middleware('web')->group(module_path($this->moduleName, '/routes/web.php'));

    }

}
