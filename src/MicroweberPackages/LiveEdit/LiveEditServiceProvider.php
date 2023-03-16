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

use Illuminate\Support\ServiceProvider;

class LiveEditServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('live_edit', function (): LiveEditManager {
            return new LiveEditManager();
        });


        \App::bind('LiveEditManager',function() {
            return new LiveEditManager();
        });


    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom((__DIR__) . '/routes/api.php');
        $this->loadRoutesFrom((__DIR__) . '/routes/web.php');
    }

}
