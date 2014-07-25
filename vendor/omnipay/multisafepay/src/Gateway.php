<?php

namespace Omnipay\MultiSafepay;

use Omnipay\Common\AbstractGateway;
use Omnipay\MultiSafepay\Message\FetchIssuersRequest;
use Omnipay\MultiSafepay\Message\FetchPaymentMethodsRequest;

/**
 * MultiSafepay gateway.
 *
 * @link https://www.multisafepay.com/downloads/handleidingen/Handleiding_connect(ENG).pdf
 */
class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'MultiSafepay';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'accountId' => '',
            'siteId' => '',
            'siteCode' => '',
            'testMode' => false,
        );
    }

    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    public function getSiteId()
    {
        return $this->getParameter('siteId');
    }

    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    public function getSiteCode()
    {
        return $this->getParameter('siteCode');
    }

    public function setSiteCode($value)
    {
        return $this->setParameter('siteCode', $value);
    }

    /**
     * Retrieve payment methods active on the given MultiSafepay
     * account.
     *
     * @param array $parameters
     *
     * @return \Omnipay\MultiSafepay\Message\FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MultiSafepay\Message\FetchPaymentMethodsRequest', $parameters);
    }

    /**
     * Retrieve iDEAL issuers.
     *
     * @param array $parameters
     *
     * @return \Omnipay\MultiSafepay\Message\FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MultiSafepay\Message\FetchIssuersRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\MultiSafepay\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MultiSafepay\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\MultiSafepay\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MultiSafepay\Message\CompletePurchaseRequest', $parameters);
    }
}
