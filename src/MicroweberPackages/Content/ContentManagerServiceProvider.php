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



        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}
