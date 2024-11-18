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

use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Menu\Http\Livewire\Admin\MenusList;
use MicroweberPackages\Menu\MenuManager;
use MicroweberPackages\Menu\Repositories\MenuRepository;
use MicroweberPackages\Menu\TranslateTables\TranslateMenu;
use Modules\Menu\Filament\Admin\MenuFilamentPlugin;
use Modules\Menu\Models\Menu;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        View::addNamespace('menu', __DIR__ . '/../resources/views');

        /**
         * @property \MicroweberPackages\Menu\MenuManager $menu_manager
         */
        $this->app->singleton('menu_manager', function ($app) {
            return new MenuManager();
        });

        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Menu::class, function () {
                return new MenuRepository();
            });
        });

        /**
         * @property MenuRepository $menu_repository
         */
        $this->app->bind('menu_repository', function ($app) {
            return $this->app->repository_manager->driver(Menu::class);;
        });

        Livewire::component('admin-menus-list', MenusList::class);


  //      FilamentRegistry::registerPlugin(MenuFilamentPlugin::class);

        FilamentRegistry::registerPage(
            \Modules\Menu\Filament\Admin\Pages\AdminMenusPage::class,
            \App\Filament\Admin\Pages\Settings::class
        );

        Event::listen(ServingFilament::class, function () {




        });


    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->translate_manager->addTranslateProvider(TranslateMenu::class);


        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations/');


    }
}
