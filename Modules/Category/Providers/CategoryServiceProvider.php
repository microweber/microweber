<?php

namespace Modules\Category\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use MicroweberPackages\Multilanguage\TranslateManager;
use Modules\Category\Filament\CategoryModuleSettings;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryManager;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Category\TranslateTables\TranslateCategory;


class CategoryServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Category';

    protected string $moduleNameLower = 'category';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {

        if(app()->bound('translate_manager')) {
            app()->translate_manager->addTranslateProvider(TranslateCategory::class);
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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
        /**
         * @property \Modules\Category\Repositories\CategoryManager $category_manager
         */
        $this->app->singleton('category_manager', function ($app) {
            return new CategoryManager();
        });

        /**
         * @property CategoryRepository $category_repository
         */
        $this->app->bind('category_repository', function ($app) {
            return new CategoryRepository();
        });

        // Register filament page for Microweber module settings
        FilamentRegistry::registerResource(\Modules\Category\Filament\Admin\Resources\CategoryResource::class);
        FilamentRegistry::registerResource(\Modules\Category\Filament\Admin\Resources\ShopCategoryResource::class);
        FilamentRegistry::registerPage(CategoryModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Category\Microweber\CategoryModule::class);

    }

}
