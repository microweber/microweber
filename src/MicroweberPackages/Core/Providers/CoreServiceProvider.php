<?php

namespace MicroweberPackages\Core\Providers;

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


          $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

          if(app()->environment() == 'testing'){
                $this->loadRoutesFrom(__DIR__ . '/../routes/tests.php');
          }

    }
}
