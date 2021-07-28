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

namespace MicroweberPackages\Content;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Content\Repositories\ContentRepositoryApi;
use MicroweberPackages\Content\Repositories\ContentRepositoryManager;
use MicroweberPackages\Content\Repositories\ContentRepository;
use MicroweberPackages\Repository\Controllers\ContentRepositoryController;


class ContentManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Content\ContentManager    $content_manager
         */
        $this->app->singleton('content_manager', function ($app) {
            return new ContentManager();
        });

        /**
         * @property \MicroweberPackages\Content\DataFieldsManager    $data_fields_manager
         */
        $this->app->singleton('data_fields_manager', function ($app) {
            return new DataFieldsManager();
        });

        /**
         * @property \MicroweberPackages\Content\AttributesManager    $attributes_manager
         */
        $this->app->singleton('attributes_manager', function ($app) {
            return new AttributesManager();
        });



        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Content::class, function () {
                 return new ContentRepository();
            });
        });



        /**
         * @property ContentRepository   $content_repository
         */
        $this->app->bind('content_repository', function ($app) {
            return $this->app->repository_manager->driver(\MicroweberPackages\Content\Content::class);;
        });





        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}
