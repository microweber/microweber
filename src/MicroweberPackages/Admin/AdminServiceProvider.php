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

namespace MicroweberPackages\Admin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        View::addNamespace('admin', __DIR__.'/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
//        $this->loadMigrationsFrom(__DIR__ . '/database/');
    }

    public function boot()
    {
        View::addNamespace('admin', __DIR__ . '/resources/views');
    }
}
