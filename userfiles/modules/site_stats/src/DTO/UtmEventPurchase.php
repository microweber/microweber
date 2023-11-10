<?php

namespace MicroweberPackages\Modules\SiteStats\DTO;

class UtmEventPurchase extends UtmEvent
{
    public $eventCategory = 'ecommerce';
    public $eventAction = 'PURCHASE';
    public $eventLabel = 'Purchase';

    public $eventData = [
        'items' => [],
        'currency' => null,
        'total' => null,
        'discount' => null,
        'transaction_id' => null,
    ];

    public function setInternalData($data)
    {

    }
}
