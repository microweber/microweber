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

namespace MicroweberPackages\Currency;


use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Core\Providers\Concerns\MergesConfig;

class CurrencyServiceProvider extends ServiceProvider
{
    use MergesConfig;
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/config/money.php', 'money');


    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/');
    }


}
