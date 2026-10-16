<?php

namespace Modules\DigitalProduct\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\DigitalProduct\Services\DigitalProductService;

class DigitalProductServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'DigitalProduct';

    protected string $moduleNameLower = 'digitalproduct';

    public function register(): void
    {
        parent::register();

        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        $this->app->singleton('digital_product_service', function () {
            return new DigitalProductService();
        });

        // Grant downloads when an order is paid.
        $this->app->register(DigitalProductEventServiceProvider::class);
    }

    public function boot(): void
    {
    }
}
