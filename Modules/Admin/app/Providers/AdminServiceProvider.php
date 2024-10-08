<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

class AdminServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Admin';

    protected string $moduleNameLower = 'admin';



    /**
     * Register the service provider.
     */
    public function register(): void
    {

      //  $this->registerTranslations();
     //   $this->registerConfig();
      // $this->registerViews();
      //  $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
       // $this->app->register(FilamentAdminPanelProvider::class);
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

    }

}
