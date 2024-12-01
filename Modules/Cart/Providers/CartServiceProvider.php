<?php

namespace Modules\Cart\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Cart\Repositories\CartManager;
use Modules\Cart\Repositories\CartRepository;


class CartServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Cart';

    protected string $moduleNameLower = 'cart';

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

        $this->app->register(CartEventServiceProvider::class);

        /**
         * @property \Modules\Cart\Repositories\CartManager    $cart_manager
         */
        $this->app->singleton('cart_manager', function ($app) {
            return new CartManager();
        });
        /**
         * @property \Modules\Cart\Repositories\CartRepository   $cart_repository
         */
        $this->app->bind('cart_repository', function () {
            return  new CartRepository();
        });
        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(CartModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Cart\Microweber\CartModule::class);

    }

}
