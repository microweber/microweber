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
        if (isset($data->order)) {
            $cartProducts = $data->order->cartProducts();
            if (isset($cartProducts['products'])) {
                foreach ($cartProducts['products'] as $cartItem) {

                    $item = new UtmItem();
                    $item->id = $cartItem['rel_id'];
                    $item->name = $cartItem['title'];
                    $item->price = $cartItem['price'];
                    $item->quantity = $cartItem['qty'];
                    $item->currency = $cartItem['currency'];

                    $this->eventData['items'][] = $item;

                }
            }
            $this->eventData['total'] = $data->order->amount;
            $this->eventData['currency'] = $data->order->currency;
            $this->eventData['transaction_id'] = $data->order->id;

        }

    }
}
