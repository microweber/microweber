<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Category\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Category\CategoryManager;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Category\Repositories\CategoryRepository;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use Illuminate\Contracts\Support\DeferrableProvider;
use MicroweberPackages\Category\TranslateTables\TranslateCategory;

class CategoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');

        parent::register();
    }

        /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations/');


        $this->app->translate_manager->addTranslateProvider(TranslateCategory::class);

        /**
         * @property \MicroweberPackages\Category\CategoryManager    $category_manager
         */
        $this->app->singleton('category_manager', function ($app) {
            return new CategoryManager();
        });

        Category::observe(BaseModelObserver::class);
        CategoryItem::observe(BaseModelObserver::class);

        View::addNamespace('category', __DIR__ . '/../resources/views');

        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Category::class, function () {
                return new CategoryRepository();
            });
        });

        /**
         * @property CategoryRepository   $category_repository
         */
        $this->app->bind('category_repository', function ($app) {
            return $this->app->repository_manager->driver(Category::class);;
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['category_manager'];
    }

}

