<?php

namespace Modules\Currency\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

class CurrencyServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Currency';

    protected string $moduleNameLower = 'currency';

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

        $this->registerConfig();

        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->mergeConfigFrom(dirname(__DIR__,2) . '/config/money.php', 'money');


    }

}
