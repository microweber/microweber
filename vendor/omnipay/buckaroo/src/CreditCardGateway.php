<?php

namespace Omnipay\Buckaroo;

use Omnipay\Common\AbstractGateway;

/**
 * Buckaroo Credit Card Gateway
 */
class CreditCardGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Buckaroo Credit Card';
    }

    public function getDefaultParameters()
    {
        return array(
            'websiteKey' => '',
            'secretKey' => '',
            'testMode' => false,
        );
    }

    public function getWebsiteKey()
    {
        return $this->getParameter('websiteKey');
    }

    public function setWebsiteKey($value)
    {
        return $this->setParameter('websiteKey', $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Buckaroo\Message\CreditCardPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Buckaroo\Message\CompletePurchaseRequest', $parameters);
    }
}
