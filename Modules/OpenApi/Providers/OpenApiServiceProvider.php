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
        parent::register();

        $this->app->register(L5SwaggerServiceProvider::class);
        $this->registerConfig();
        $this->registerViews();

        // Deep-replace the vendor l5-swagger config with the module's
        // overrides. `mergeConfigFrom` only shallow-merges top-level
        // keys, which means nested keys like
        // documentations.default.paths.annotations get clobbered by the
        // vendor defaults and our scan paths are never honoured. A
        // recursive replace ensures our annotation list (restricted to
        // the headless module API controllers) wins.
        $this->app->booting(function () {
            $path = module_path($this->moduleName, 'config/l5-swagger.php');
            if (!file_exists($path)) {
                return;
            }

            $moduleConfig = require $path;
            $current = config('l5-swagger', []);
            config(['l5-swagger' => array_replace_recursive($current, $moduleConfig)]);
        });
    }

}
