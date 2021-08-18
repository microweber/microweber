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

namespace MicroweberPackages\Cart;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Cart\Repositories\CartRepository;

class CartManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        /**
         * @property \MicroweberPackages\Cart\CartManager    $cart_manager
         */
        $this->app->singleton('cart_manager', function ($app) {
            return new CartManager();
        });



        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Cart::class, function () {
                return new CartRepository();
            });
        });


        /**
         * @property CartRepository   $cart_repository
         */
        $this->app->bind('cart_repository', function () {
            return $this->app->repository_manager->driver(Cart::class);;
        });


    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['cart_manager'];
    }
}