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

namespace MicroweberPackages\LiveEdit;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class LiveEditServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        $this->loadRoutesFrom((__DIR__) . '/routes/api.php');
        //$this->loadRoutesFrom((__DIR__) . '/routes/web.php');
    }

}
