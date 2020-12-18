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

namespace MicroweberPackages\Queue\Providers;

use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations/');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        /*
        app()->terminating(function () {
            $scheduler = new Event();
            $scheduler->registerShutdownEvent(function () {
                app()->make("\MicroweberPackages\Queue\Http\Controllers\ProcessQueueController")->handle();
            });
        });*/

    }
}

