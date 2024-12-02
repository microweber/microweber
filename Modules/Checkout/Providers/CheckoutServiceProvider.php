<?php

namespace Modules\Checkout\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Checkout\Repositories\CheckoutManager;


class CheckoutServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Checkout';

    protected string $moduleNameLower = 'checkout';

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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        /**
         * @property \Modules\Checkout\Repositories\CheckoutManager    $checkout_manager
         */
        $this->app->singleton('checkout_manager', function ($app) {
            return new CheckoutManager();
        });


        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(CheckoutModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Checkout\Microweber\CheckoutModule::class);

    }

}
