<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Mollie Complete Purchase Request
 *
 * @method \Omnipay\Mollie\Message\CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends FetchTransactionRequest
{
    public function getData()
    {
        $this->validate('apiKey');

        $data = array();
        $data['id'] = $this->getTransactionReference();

        if (!isset($data['id'])) {
            $data['id'] = $this->httpRequest->request->get('id');
        }

        if (empty($data['id'])) {
            throw new InvalidRequestException("The id parameter is required");
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('GET', '/payments/' . $data['id']);

        return $this->response = new CompletePurchaseResponse($this, $httpResponse->json());
    }
}
