<?php

namespace Omnipay\Buckaroo\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Buckaroo Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('websiteKey', 'secretKey', 'amount');

        $originalData = $this->httpRequest->request->all();
        $data = array_change_key_case($originalData, CASE_UPPER);

        $signature = isset($data['BRQ_SIGNATURE']) ? strtolower($data['BRQ_SIGNATURE']) : null;

        if ($signature !== $this->generateSignature($originalData)) {
            throw new InvalidRequestException('Incorrect signature');
        }

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
