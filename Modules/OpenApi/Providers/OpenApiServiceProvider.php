<?php

namespace Modules\OpenApi\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use L5Swagger\L5SwaggerServiceProvider;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

class OpenApiServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'OpenApi';

    protected string $moduleNameLower = 'openapi';


    /**
     * Register the service provider.
     */
    public function register(): void
    {
       $this->app->register(L5SwaggerServiceProvider::class);
        $this->registerConfig();
        $this->registerViews();
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/swagger.php'));

    }

}
