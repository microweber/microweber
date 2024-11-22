<?php

namespace Modules\Order\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Order\Filament\Admin\Resources\OrderResource;
use Modules\Order\Repositories\OrderManager;
use Modules\Order\Repositories\OrderRepository;


class OrderServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Order';

    protected string $moduleNameLower = 'order';

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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
        /**
         * @property \Modules\Order\Repositories\OrderManager    $order_manager
         */
        $this->app->singleton('order_manager', function ($app) {
            return new OrderManager();
        });

        /**
         * @property \Modules\Order\Repositories\OrderRepository    $order_repository
         */
        $this->app->singleton('order_repository', function ($app) {
            return new OrderRepository();
        });


        FilamentRegistry::registerResource(OrderResource::class);


    }

}
