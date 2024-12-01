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
use Modules\Cart\Models\Cart;
use Modules\Cart\Repositories\CartRepository;

class CartManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {


        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Cart::class, function () {
                return new CartRepository();
            });
        });



    }

    public function boot()
    {
     //   $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
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
