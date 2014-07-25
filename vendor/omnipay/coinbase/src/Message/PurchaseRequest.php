<?php

namespace Omnipay\Coinbase\Message;

/**
 * Coinbase Purchase Request
 *
 * @method \Omnipay\Coinbase\Message\PurchaseResponse send()
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency', 'description');

        $data = array();
        $data['account_id'] = $this->getAccountId() ?: null;
        $data['button'] = array();
        $data['button']['name'] = $this->getDescription();
        $data['button']['price_string'] = $this->getAmount();
        $data['button']['price_currency_iso'] = $this->getCurrency();
        $data['button']['success_url'] = $this->getReturnUrl();
        $data['button']['cancel_url'] = $this->getCancelUrl();
        $data['button']['callback_url'] = $this->getNotifyUrl();

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('POST', '/buttons', $data);

        return $this->response = new PurchaseResponse($this, $httpResponse->json());
    }
}
