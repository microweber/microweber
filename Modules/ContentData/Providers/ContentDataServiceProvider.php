<?php

namespace Modules\ContentData\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

class ContentDataServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'ContentData';

    protected string $moduleNameLower = 'contentdata';


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

    }

    public function boot()
    {
         $this->app->translate_manager->addTranslateProvider(TranslateContentData::class);
    }
}
