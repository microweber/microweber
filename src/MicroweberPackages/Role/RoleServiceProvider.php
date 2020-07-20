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

namespace MicroweberPackages\Role;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Role\Http\Controllers\IndexController;
use MicroweberPackages\Role\Http\Controllers\RoleController;


class RoleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {




//        mw()->module_manager->register([
//           'public_folder'=> 'users/roles',
//           'name'=> 'User Roles',
//           'icon'=> __DIR__ . '/Assets/icon.png',
//           'controller'=>IndexController::class,
//           'admin_controller'=>IndexController::class
//        ]);

        $this->app->module_manager->register(
            ['name' => 'Bojkata',

            'author' => 'Microweber',
            'description' => 'User Roles',
            'website' => 'http://microweber.com/',
            'help' => 'http://microweber.info/modules',
            'version' => 0.19,
            'ui' => true,
            'ui_admin' => true,
            'installed' => true,
            'is_system' => false,
            'position' => 30,
            'categories' => 'admin',

            'module' => 'users/bojkata',
            'type' => 'users/bojkata',
            'id' => 'users/bojkata',
            'controllers' => [
                'index' => "MicroweberPackages\Role\Http\Controllers\IndexController@index",
                'admin' => "MicroweberPackages\Role\Http\Controllers\IndexController@admin",
            ],
        ]);

        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

        //dd(userfiles_path());
       // dd(asset('asdas'));

//        $this->publishes([
//            __DIR__.'/../../views' => userfiles_path().'assets/views/vendor/users/bojkata',
//        ]);

    }
}
