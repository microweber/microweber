<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 9/24/2020
 * Time: 3:38 PM
 */

namespace MicroweberPackages\Shop\Offers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Product\Product;
use MicroweberPackages\Shop\Offers\Observers\ShopOffersObserver;

class ShopOffersServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }
}