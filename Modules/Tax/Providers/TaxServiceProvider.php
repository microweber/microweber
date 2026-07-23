<?php

namespace Modules\Tax\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Tax\Filament\Admin\Resources\TaxResource;
use Modules\Tax\Filament\Admin\Resources\TaxRateResource;
use Modules\Tax\Services\TaxCalculator;
use Modules\Tax\Services\TaxManager;

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
         * @property \Modules\Tax\Services\TaxManager $tax_manager
         */
        app()->singleton('tax_manager', function ($app) {
            return new TaxManager();
        });

        /**
         * @property \Modules\Tax\Services\TaxCalculator $tax_calculator
         */
        app()->singleton('tax_calculator', function ($app) {
            return new TaxCalculator();
        });



        FilamentRegistry::registerResource(TaxResource::class);
        FilamentRegistry::registerResource(TaxRateResource::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'Tax Settings', '/admin/taxes',
            ['tax', 'taxes', 'tax rate', 'vat', 'sales tax', 'tax settings',
             'tax class', 'tax zone', 'tax calculation'],
            'Shop Settings', ['Section' => 'Shop Settings'],
        );
    }

}
