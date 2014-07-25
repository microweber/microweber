<?php

namespace Omnipay\Coinbase\Message;

/**
 * Coinbase Fetch Transaction Request
 *
 * @method \Omnipay\Coinbase\Message\Response send()
 */
class FetchTransactionRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        return array('id' => $this->getTransactionReference());
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('GET', '/orders/'.$data['id']);

        return $this->response = new Response($this, $httpResponse->json());
    }
}
