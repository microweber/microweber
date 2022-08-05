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

namespace MicroweberPackages\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Menu\Menu;
use MicroweberPackages\Menu\MenuManager;
use MicroweberPackages\Menu\Repositories\MenuRepository;
use MicroweberPackages\Menu\TranslateTables\TranslateMenu;
use MicroweberPackages\Multilanguage\TranslateTablesRegistrator;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->translate_manager->addTranslateProvider(TranslateMenu::class);

        /**
         * @property \MicroweberPackages\Menu\MenuManager    $menu_manager
         */
        $this->app->singleton('menu_manager', function ($app) {
            return new MenuManager();
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations/');


        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Menu::class, function () {
                return new MenuRepository();
            });
        });

        /**
         * @property MenuRepository   $menu_repository
         */
        $this->app->bind('menu_repository', function ($app) {
            return $this->app->repository_manager->driver(Menu::class);;
        });
    }
}
