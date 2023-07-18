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

namespace MicroweberPackages\Module;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\Admin\Facades\AdminManager;
use MicroweberPackages\Marketplace\Http\Livewire\Admin\Marketplace;
use MicroweberPackages\Module\Http\Livewire\Admin\AskForModuleUninstallModal;
use MicroweberPackages\Module\Http\Livewire\Admin\ListModules;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\AligmentOption;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\DropdownOption;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\IconPickerOption;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\LinkPickerOption;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\RadioOption;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\CheckboxOption;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\RangeSliderOption;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\TextareaOption;
use MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption\TextOption;
use MicroweberPackages\Module\Repositories\ModuleRepository;


class ModuleServiceProvider extends ServiceProvider
{

    public function registerMenu()
    {
        AdminManager::getMenuInstance('left_menu_top')->addChild('Modules', [
            'uri' => route('admin.module.index'),
            'attributes'=>[
                'icon'=>' <svg fill="currentColor"style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m390 976-68-120H190l-90-160 68-120-68-120 90-160h132l68-120h180l68 120h132l90 160-68 120 68 120-90 160H638l-68 120H390Zm248-440h86l44-80-44-80h-86l-45 80 45 80ZM438 656h84l45-80-45-80h-84l-45 80 45 80Zm0-240h84l46-81-45-79h-86l-45 79 46 81ZM237 536h85l45-80-45-80h-85l-45 80 45 80Zm0 240h85l45-80-45-80h-86l-44 80 45 80Zm200 120h86l45-79-46-81h-84l-46 81 45 79Zm201-120h85l45-80-45-80h-85l-45 80 45 80Z"/></svg>'
            ]
        ]);

        AdminManager::getMenuInstance('left_menu_top')
            ->menuItems
            ->getChild('Modules')
            ->setExtra('orderNumber', 4);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Event::listen(ServingAdmin::class, [$this, 'registerMenu']);

        View::addNamespace('module', __DIR__.'/resources/views');


        $this->app->singleton('module_manager', function ($app) {
            return new ModuleManager();
        });

        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(\MicroweberPackages\Module\Models\Module::class, function () {
                return new \MicroweberPackages\Module\Repositories\ModuleRepository();
            });
        });


        /**
         * @property ModuleRepository $module_repository
         */
        $this->app->bind('module_repository', function () {
            return $this->app->repository_manager->driver(\MicroweberPackages\Module\Models\Module::class);;
        });

        Livewire::component('microweber-module-option::text', TextOption::class);
        Livewire::component('microweber-module-option::textarea', TextareaOption::class);
        Livewire::component('microweber-module-option::icon-picker', IconPickerOption::class);
        Livewire::component('microweber-module-option::link-picker', LinkPickerOption::class);
        Livewire::component('microweber-module-option::alignment', AligmentOption::class);
        Livewire::component('microweber-module-option::range-slider', RangeSliderOption::class);
        Livewire::component('microweber-module-option::dropdown', DropdownOption::class);
        Livewire::component('microweber-module-option::radio', RadioOption::class);
        Livewire::component('microweber-module-option::checkbox', CheckboxOption::class);

    }


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations/');

        Livewire::component('admin-modules-list', ListModules::class);
        Livewire::component('admin-ask-for-module-uninstall-modal', AskForModuleUninstallModal::class);

        $this->app->bind('module', function () {
            return new Module();
        });

        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('ModuleManager', \MicroweberPackages\Module\Facades\ModuleManager::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }
}
