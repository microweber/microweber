<?php

namespace Omnipay\Mollie\Message;

/**
 * Mollie Fetch PaymentMethods Request
 *
 * @method \Omnipay\Mollie\Message\FetchPaymentMethodsResponse send()
 */
class FetchPaymentMethodsRequest extends AbstractRequest
{
    /**
     * @return null
     */
    public function getData()
    {
        $this->validate('apiKey');
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('GET', '/methods');

        return $this->response = new FetchPaymentMethodsResponse($this, $httpResponse->json());
    }
}
