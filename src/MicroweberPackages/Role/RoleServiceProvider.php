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

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Role\Http\Controllers\IndexController;
use MicroweberPackages\Role\Http\Controllers\RoleController;


class RoleServiceProvider extends ServiceProvider
{

    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, array_merge( $config,require $path,));
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/permission.php', 'permission');

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

       /* View::addNamespace('role', __DIR__.'/resources/views');


        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');*/
    }
}
