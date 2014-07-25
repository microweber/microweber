<?php

namespace Omnipay\Coinbase\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Coinbase Response
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['order']['status']) &&
            'completed' === $this->data['order']['status'];
    }

    public function getMessage()
    {
        if (isset($this->data['error'])) {
            return $this->data['error'];
        } elseif (isset($this->data['errors'])) {
            return implode(', ', $this->data['errors']);
        } elseif (isset($this->data['order']['status'])) {
            return $this->data['order']['status'];
        }
    }

    public function getTransactionReference()
    {
        if (isset($this->data['order']['id'])) {
            return $this->data['order']['id'];
        }
    }
}
