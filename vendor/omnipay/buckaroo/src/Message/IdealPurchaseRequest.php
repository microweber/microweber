<?php

namespace Omnipay\Buckaroo\Message;

/**
 * Buckaroo iDeal Purchase Request
 */
class IdealPurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = parent::getData();
        $data['Brq_payment_method'] = 'ideal';

        return $data;
    }
}
