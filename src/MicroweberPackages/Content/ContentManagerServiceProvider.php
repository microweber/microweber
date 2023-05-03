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
use MicroweberPackages\Content\Repositories\ContentRepository;
use MicroweberPackages\Content\Models\Content;


class ContentManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . DS . 'migrations');
        $this->loadRoutesFrom(__DIR__ . DS . 'routes' . DS . 'api.php');
        $this->loadRoutesFrom(__DIR__ . DS . 'routes' . DS . 'web.php');
        /**
         * @property ContentRepository   $content_repository
         */
        $this->app->bind('content_repository', function ($app) {
            return $this->app->repository_manager->driver(\MicroweberPackages\Content\Models\Content::class);;
        });


        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(\MicroweberPackages\Content\Models\Content::class, function () {
                return new ContentRepository();
            });
        });


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


        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}
