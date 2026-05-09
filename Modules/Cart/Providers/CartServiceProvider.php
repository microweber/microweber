<?php

namespace Modules\Cart\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Cart\Filament\CartAddModuleSettings;
use Modules\Cart\Microweber\CartAddModule;
use Modules\Cart\Repositories\CartManager;
use Modules\Cart\Repositories\CartRepository;
use Modules\Cart\Services\CartService;
use Modules\Cart\Services\CartTotalsService;
use Modules\Cart\Services\CartCouponService;


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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        $this->app->register(CartEventServiceProvider::class);

        // Register cart repository
        $this->app->singleton('cart_repository', function ($app) {
            return new CartRepository();
        });

        // Register cart services
        $this->app->singleton('cart_service', function ($app) {
            return new CartService($app);
        });

        $this->app->singleton('cart_totals_service', function ($app) {
            return new CartTotalsService($app);
        });

        $this->app->singleton('cart_coupon_service', function ($app) {
            return new CartCouponService($app);
        });

        // Keep cart_manager for backward compatibility
        $this->app->singleton('cart_manager', function ($app) {
            return new CartManager($app);
        });

        // AI-105 / TICKET-AY (cycle-118 2026-05-09): Module Contracts
        // DI binding. Callers can now type-hint
        // `Modules\Cart\Contracts\CartManagerContract` and receive
        // the singleton `cart_manager` instance. Drift detection +
        // mock-friendly tests + future ContractValidator scan.
        $this->app->singleton(
            \Modules\Cart\Contracts\CartManagerContract::class,
            fn ($app) => $app->make('cart_manager')
        );
        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(CartAddModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Cart\Microweber\CartModule::class);
        Microweber::module(CartAddModule::class);

    }

}
