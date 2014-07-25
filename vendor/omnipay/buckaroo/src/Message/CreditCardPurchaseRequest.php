<?php

namespace Omnipay\Buckaroo\Message;

/**
 * Buckaroo Credit Card Purchase Request
 */
class CreditCardPurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = parent::getData();
        $data['Brq_payment_method'] = 'visa';

        return $data;
    }
}
