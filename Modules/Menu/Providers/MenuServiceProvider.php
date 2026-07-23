<?php

namespace Modules\Menu\Providers;

use Livewire\Livewire;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;
use MicroweberPackages\Microweber\Facades\Microweber;
use MicroweberPackages\Multilanguage\TranslateManager;
use Modules\Menu\Filament\Admin\MenuFilamentPlugin;
use Modules\Menu\Filament\Admin\Pages\AdminMenusPage;
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
        if (app()->bound('translate_manager')) {
            app()->translate_manager->addTranslateProvider(TranslateMenu::class);
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
         * @property MenuRepository $menu_repository
         */
        $this->app->bind('menu_repository', function ($app) {
            return new MenuRepository();
        });

        /**
         * @property \Modules\Menu\Repositories\MenuManager $menu_manager
         */
        $this->app->singleton('menu_manager', function ($app) {
            return new MenuManager();
        });


        // Register filament page for Microweber module settings

        FilamentRegistry::registerPage(AdminMenusPage::class);
        FilamentRegistry::registerPage(MenuModuleSettings::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'Menu Management', '/admin/settings/menus',
            ['menu', 'menus', 'navigation', 'nav', 'menu items',
             'header menu', 'footer menu', 'site menu'],
            'Admin Pages', ['Section' => 'Website'],
        );
        Livewire::component('admin-menus-list', MenusList::class);

        // Register Microweber module
        Microweber::module(\Modules\Menu\Microweber\MenuModule::class);

        LiveEditManager::addScript('menu-module-quick-settings', asset('modules/menu/js/menu-quick-settings.js'));

    }

}
