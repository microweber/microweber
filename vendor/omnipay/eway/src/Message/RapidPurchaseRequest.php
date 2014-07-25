<?php

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * eWAY Rapid 3.0 Purchase Request
 */
class RapidPurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.ewaypayments.com';
    protected $testEndpoint = 'https://api.sandbox.ewaypayments.com';

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = array();
        $data['Method'] = 'ProcessPayment';
        $data['DeviceID'] = 'https://github.com/adrianmacneil/omnipay';
        $data['CustomerIP'] = $this->getClientIp();
        $data['RedirectUrl'] = $this->getReturnUrl();

        $data['Payment'] = array();
        $data['Payment']['TotalAmount'] = $this->getAmountInteger();
        $data['Payment']['InvoiceNumber'] = $this->getTransactionId();
        $data['Payment']['InvoiceDescription'] = $this->getDescription();
        $data['Payment']['CurrencyCode'] = $this->getCurrency();

        $data['Customer'] = array();
        $card = $this->getCard();
        if ($card) {
            $data['Customer']['FirstName'] = $card->getFirstName();
            $data['Customer']['LastName'] = $card->getLastName();
            $data['Customer']['CompanyName'] = $card->getCompany();
            $data['Customer']['Street1'] = $card->getAddress1();
            $data['Customer']['Street2'] = $card->getAddress2();
            $data['Customer']['City'] = $card->getCity();
            $data['Customer']['State'] = $card->getState();
            $data['Customer']['PostalCode'] = $card->getPostCode();
            $data['Customer']['Country'] = strtolower($card->getCountry());
            $data['Customer']['Email'] = $card->getEmail();
            $data['Customer']['Phone'] = $card->getPhone();
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, json_encode($data))
            ->setAuth($this->getApiKey(), $this->getPassword())
            ->send();

        return $this->response = new RapidResponse($this, $httpResponse->json());
    }

    public function getEndpoint()
    {
        return $this->getEndpointBase().'/CreateAccessCode.json';
    }

    public function getEndpointBase()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
