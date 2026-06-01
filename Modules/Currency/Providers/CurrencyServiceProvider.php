<?php

namespace Modules\Currency\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Currency\Filament\Admin\Resources\CurrencyResource;
use Modules\Currency\Filament\Admin\Resources\ExchangeRateResource;
use Modules\Currency\Services\CurrencyManager;
use Modules\Currency\Services\CurrencyConversionService;
use Modules\Currency\Http\Middleware\CurrencyMiddleware;

class CurrencyServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Currency';

    protected string $moduleNameLower = 'currency';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerViews();
        $this->registerMiddleware();
        $this->registerBladeDirectives();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerServices();
        $this->registerHelpers();

        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/money.php', 'money');
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/currency.php', 'currency');

        FilamentRegistry::registerResource(CurrencyResource::class);
        FilamentRegistry::registerResource(ExchangeRateResource::class);
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $this->loadViewsFrom(
            module_path($this->moduleName, 'resources/views'),
            $this->moduleNameLower
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->singleton(CurrencyManager::class, function ($app) {
            return new CurrencyManager();
        });

        $this->app->singleton(CurrencyConversionService::class, function ($app) {
            return new CurrencyConversionService();
        });
    }

    /**
     * Register middleware.
     *
     * @return void
     */
    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('currency', CurrencyMiddleware::class);
    }

    /**
     * Register helper functions.
     *
     * @return void
     */
    protected function registerHelpers(): void
    {
        $helperPath = module_path($this->moduleName, 'Support/helpers.php');
        if (file_exists($helperPath)) {
            require_once $helperPath;
        }
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('currency', function ($expression) {
            return "<?php echo currency_format({$expression}); ?>";
        });

        Blade::directive('currencySymbol', function ($expression) {
            return "<?php echo current_currency_symbol(); ?>";
        });

        Blade::directive('currencyCode', function ($expression) {
            return "<?php echo current_currency_code(); ?>";
        });
    }
}
