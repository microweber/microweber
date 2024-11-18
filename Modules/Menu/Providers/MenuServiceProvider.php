<?php

namespace Modules\Menu\Providers;

use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Menu\Filament\Admin\MenuFilamentPlugin;
use Modules\Menu\Filament\MenuModuleSettings;
use Modules\Menu\Livewire\Admin\MenusList;
use Modules\Menu\Models\Menu;
use Modules\Menu\Repositories\MenuManager;
use Modules\Menu\Repositories\MenuRepository;
use Modules\Menu\TranslateTables\TranslateMenu;

class MenuServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Menu';

    protected string $moduleNameLower = 'menu';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {

        $this->app->translate_manager->addTranslateProvider(TranslateMenu::class);

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

        /**
         * @property \Modules\Menu\Repositories\MenuManager $menu_manager
         */
        $this->app->singleton('menu_manager', function ($app) {
            return new MenuManager();
        });



        // Register filament page for Microweber module settings
        FilamentRegistry::registerPlugin(MenuFilamentPlugin::class);
        FilamentRegistry::registerPage(MenuModuleSettings::class);
        Livewire::component('admin-menus-list', MenusList::class);

        // Register Microweber module
        Microweber::module(\Modules\Menu\Microweber\MenuModule::class);


    }

}
