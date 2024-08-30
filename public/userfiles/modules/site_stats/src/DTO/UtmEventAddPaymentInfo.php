<?php

namespace MicroweberPackages\Modules\SiteStats\DTO;

class UtmEventAddPaymentInfo extends UtmEventBeginCheckout
{
    public $eventCategory = 'ecommerce';
    public $eventAction = 'ADD_PAYMENT_INFO';
    public $eventLabel = 'Add payment info';

}
