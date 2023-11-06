<?php

namespace MicroweberPackages\Modules\SiteStats\DTO;

class UtmEventActionBeginCheckout extends UtmEvent
{
    public $eventCategory = 'ecommerce';
    public $eventAction = 'BEGIN_CHECKOUT';
    public $eventLabel = 'Begin Checkout';

    public $eventData = [
        'items' => [],
        'currency' => null,
        'total' => null,
        'discount' => null,
    ];

    public function setInternalData($data)
    {
        if (isset($data->cartData)) {
            foreach ($data->cartData['cart'] as $cartItem) {
                $item = new UtmItem();
                if (isset($cartItem['rel_id'])) {
                    $item->id = $cartItem['rel_id'];
                }
                if (isset($cartItem['title'])) {
                    $item->name = $cartItem['title'];
                }
                if (isset($cartItem['brand'])) {
                    $item->brand = $cartItem['brand'];
                }
                if (isset($cartItem['category'])) {
                    $item->category = $cartItem['category'];
                }
                if (isset($cartItem['price'])) {
                    $item->price = $cartItem['price'];
                }
                if (isset($cartItem['qty'])) {
                    $item->quantity = $cartItem['qty'];
                }
                if (isset($cartItem['currency'])) {
                    $item->currency = $cartItem['currency'];
                }
                if (isset($cartItem['coupon_code'])) {
                    $item->coupon = $cartItem['coupon_code'];
                }
                $this->eventData['items'][] = $item;
            }
            if (isset($data->cartData['total'])) {
                $this->eventData['total'] = $data->cartData['total'];
            }
            if (isset($data->cartData['discount'])) {
                $this->eventData['discount'] = $data->cartData['discount'];
            }
            if (isset($data->cartData['currency'])) {
                $this->eventData['currency'] = $data->cartData['currency'];
            }
        }

    }
}
