<?php

namespace Omnipay\Buckaroo\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Buckaroo Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    const SUCCESS = '190';

    public function isSuccessful()
    {
        return static::SUCCESS === $this->getCode();
    }

    public function getCode()
    {
        if (isset($this->data['BRQ_STATUSCODE'])) {
            return $this->data['BRQ_STATUSCODE'];
        }
    }

    public function getMessage()
    {
        if (isset($this->data['BRQ_STATUSMESSAGE'])) {
            return $this->data['BRQ_STATUSMESSAGE'];
        }
    }

    public function getTransactionReference()
    {
        if (isset($this->data['BRQ_PAYMENT'])) {
            return $this->data['BRQ_PAYMENT'];
        }
    }
}
