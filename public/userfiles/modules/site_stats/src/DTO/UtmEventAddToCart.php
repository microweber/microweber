<?php

namespace MicroweberPackages\Modules\SiteStats\DTO;

class UtmEventAddToCart extends UtmEventBeginCheckout
{
    public $eventCategory = 'ecommerce';
    public $eventAction = 'ADD_TO_CART';
    public $eventLabel = 'Add To Cart';

}
