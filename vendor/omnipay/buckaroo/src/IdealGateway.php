<?php

namespace Omnipay\Buckaroo;

/**
 * Buckaroo iDeal Gateway
 */
class IdealGateway extends CreditCardGateway
{
    public function getName()
    {
        return 'Buckaroo iDeal';
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Buckaroo\Message\IdealPurchaseRequest', $parameters);
    }
}
