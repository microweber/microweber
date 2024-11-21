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
use Livewire\Livewire;
use MicroweberPackages\Category\Http\Livewire\Admin\CategoryBulkMoveModalComponent;
use MicroweberPackages\Category\Http\Livewire\Admin\CategoryManageComponent;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use Modules\Category\Models\Category;
use Modules\Category\Models\CategoryItem;
use Modules\Category\Repositories\CategoryRepository;

class CategoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
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





        Category::observe(BaseModelObserver::class);
        CategoryItem::observe(BaseModelObserver::class);

        View::addNamespace('category', dirname(__DIR__) . '/resources/views');

        Livewire::component('admin-category-manage', CategoryManageComponent::class);

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

