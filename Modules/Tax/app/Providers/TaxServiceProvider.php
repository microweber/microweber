<?php

namespace Modules\Tax\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Tax\Filament\Admin\Resources\TaxResource;
use Modules\Tax\TaxManager;

class TaxServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Tax';

    protected string $moduleNameLower = 'tax';


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
         * @property \Modules\Tax\TaxManager $tax_manager
         */
        app()->singleton('tax_manager', function ($app) {
            return new TaxManager();
        });



        FilamentRegistry::registerResource(TaxResource::class);
    }

}
