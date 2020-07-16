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
use MicroweberPackages\Roles\Http\Controllers\RoleController;


class RoleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        mw()->module_manager->register_module([
           'public_folder'=> 'users/roles',
           'name'=> 'User Roles',
           'icon'=> __DIR__ . '/Assets/icon.png',
           'admin_controller'=>RoleController::class
        ]);

        $this->loadRoutesFrom(__DIR__.'/routes/admin.php');
    }
}
