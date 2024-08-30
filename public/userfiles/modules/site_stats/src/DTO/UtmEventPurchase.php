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
        $order = false;
        if (isset($data->order)) {
            $order = $data->order;
        }

        if (isset($order) && $order) {

            $cartProducts = $order->cartProducts();

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

            $this->eventData['total'] = $order->amount;
            $this->eventData['currency'] = $order->currency;
            $this->eventData['transaction_id'] = $order->transaction_id;

            $this->eventData['customer'] = [
                'first_name'=>$order->first_name,
                'last_name'=>$order->last_name,
                'email'=>$order->email,
                'phone'=>$order->phone,
            ];

        }

    }
}
