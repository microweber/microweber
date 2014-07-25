<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Pro Purchase Request
 */
class ProPurchaseRequest extends ProAuthorizeRequest
{
    public function getData()
    {
        $data = parent::getData();
        $data['PAYMENTACTION'] = 'Sale';

        return $data;
    }
}
