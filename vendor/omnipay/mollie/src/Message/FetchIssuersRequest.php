<?php

namespace Omnipay\Mollie\Message;

/**
 * Mollie Fetch Issuers Request
 *
 * @method \Omnipay\Mollie\Message\FetchIssuersResponse send()
 */
class FetchIssuersRequest extends AbstractRequest
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
        $httpResponse = $this->sendRequest('GET', '/issuers');

        return $this->response = new FetchIssuersResponse($this, $httpResponse->json());
    }
}
