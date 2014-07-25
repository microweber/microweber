<?php

namespace Omnipay\Mollie\Message;

class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    public function isSuccessful()
    {
        return !$this->isRedirect() && !isset($this->data['error']);
    }

    public function getMessage()
    {
        if (isset($this->data['error'])) {
            return $this->data['error']['message'];
        }
    }
}
