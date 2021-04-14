<?php

namespace MicroweberPackages\Offer\Providers;

use Illuminate\Support\ServiceProvider;

class OfferServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        require_once (__DIR__.'/offers_functions.php');
    }

}
