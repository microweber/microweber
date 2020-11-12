<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 9/24/2020
 * Time: 3:38 PM
 */

namespace MicroweberPackages\Shop\Offers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Product\Events\OrderWasCreated;
use MicroweberPackages\Product\Events\OrderWasUpdated;
use MicroweberPackages\Shop\Offers\Listeners\AddSpecialPriceProductListener;
use MicroweberPackages\Shop\Offers\Listeners\EditSpecialPriceProductListener;


class ShopOffersEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        OrderWasCreated::class => [
            AddSpecialPriceProductListener::class
        ],
        OrderWasUpdated::class => [
            EditSpecialPriceProductListener::class
        ]
    ];
}