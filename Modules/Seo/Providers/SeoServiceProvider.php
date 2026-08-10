<?php

namespace Modules\Seo\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Seo\Services\SeoMetadataService;

class SeoServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Seo';

    protected string $moduleNameLower = 'seo';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        
        // Register SEO service as singleton
        $this->app->singleton(SeoMetadataService::class, function ($app) {
            return new SeoMetadataService();
        });
    }

    /**
     * Register Blade directives
     */
    protected function registerBladeDirectives(): void
    {
        // @seoMetaTags($content) - Renders all meta tags for a content item
        Blade::directive('seoMetaTags', function ($expression) {
            return "<?php echo app(\Modules\Seo\Services\SeoMetadataService::class)->renderMetaTags({$expression}); ?>";
        });

        // @seoTitle($content) - Renders title meta tag
        Blade::directive('seoTitle', function ($expression) {
            return "<?php echo app(\Modules\Seo\Services\SeoMetadataService::class)->renderTitle({$expression}); ?>";
        });

        // @seoDescription($content) - Renders description meta tag
        Blade::directive('seoDescription', function ($expression) {
            return "<?php echo app(\Modules\Seo\Services\SeoMetadataService::class)->renderDescription({$expression}); ?>";
        });

        // @seoOpenGraph($content) - Renders Open Graph meta tags
        Blade::directive('seoOpenGraph', function ($expression) {
            return "<?php echo app(\Modules\Seo\Services\SeoMetadataService::class)->renderOpenGraph({$expression}); ?>";
        });

        // @seoTwitterCard($content) - Renders Twitter Card meta tags
        Blade::directive('seoTwitterCard', function ($expression) {
            return "<?php echo app(\Modules\Seo\Services\SeoMetadataService::class)->renderTwitterCard({$expression}); ?>";
        });
    }
}
