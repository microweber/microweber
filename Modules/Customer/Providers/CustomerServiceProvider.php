<?php

namespace Modules\Customer\Providers;

use MicroweberPackages\AiTools\Support\RegistersAiTools;
use Modules\Customer\Tools\CustomerLookupTool;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Customer\Filament\CustomerResource;

class CustomerServiceProvider extends BaseModuleServiceProvider
{
    use RegistersAiTools;

    protected string $moduleName = 'Customer';

    protected string $moduleNameLower = 'customer';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerAiTools([
            CustomerLookupTool::class,
        ]);



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
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        $this->app->register(CustomerEventServiceProvider::class);
        FilamentRegistry::registerResource(CustomerResource::class);
    }

}
