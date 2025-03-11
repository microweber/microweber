<?php

namespace Modules\ContentData\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Multilanguage\TranslateManager;
use Modules\ContentData\Repositories\DataFieldsManager;
use Modules\ContentData\TranslateTables\TranslateContentData;

class ContentDataServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'ContentData';

    protected string $moduleNameLower = 'contentdata';
    public function boot()
    {
        if(app()->bound('translate_manager')) {
            app()->translate_manager->addTranslateProvider(TranslateContentData::class);
        }


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
        /**
         * @property \Modules\ContentData\Repositories\DataFieldsManager    $data_fields_manager
         */
        $this->app->singleton('data_fields_manager', function ($app) {
            return new DataFieldsManager();
        });



    }


}
