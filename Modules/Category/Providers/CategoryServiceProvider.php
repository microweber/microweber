<?php

namespace Modules\Category\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Category\CategoryManager;
use MicroweberPackages\Category\TranslateTables\TranslateCategory;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;


class CategoryServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Category';

    protected string $moduleNameLower = 'category';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        app()->translate_manager->addTranslateProvider(TranslateCategory::class);


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
         * @property \MicroweberPackages\Category\CategoryManager    $category_manager
         */
        $this->app->singleton('category_manager', function ($app) {
            return new CategoryManager();
        });

        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(CategoryModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Category\Microweber\CategoryModule::class);

    }

}
