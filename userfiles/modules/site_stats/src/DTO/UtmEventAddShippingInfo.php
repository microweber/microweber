<?php

namespace MicroweberPackages\Modules\SiteStats\DTO;

class UtmEventAddShippingInfo extends UtmEventBeginCheckout
{
    public $eventCategory = 'ecommerce';
    public $eventAction = 'ADD_SHIPPING_INFO';
    public $eventLabel = 'Add shipping info';

}
