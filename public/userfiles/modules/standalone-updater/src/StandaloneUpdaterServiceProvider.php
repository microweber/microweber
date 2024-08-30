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

namespace MicroweberPackages\Modules\StandaloneUpdater;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class StandaloneUpdaterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {
        View::addNamespace('standalone-updater', __DIR__ . '/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');


    }
}
