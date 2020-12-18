<?php
namespace MicroweberPackages\Core;

use Illuminate\Support\ServiceProvider;


class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
