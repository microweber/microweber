<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Common\Issuer;
use Omnipay\Common\Message\FetchIssuersResponseInterface;

class FetchIssuersResponse extends AbstractResponse implements FetchIssuersResponseInterface
{
    /**
     * Return available issuers as an associative array.
     *
     * @return \Omnipay\Common\Issuer[]
     */
    public function getIssuers()
    {
        if (isset($this->data['data'])) {
            $issuers = array();
            foreach ($this->data['data'] as $issuer) {
                $issuers[] = new Issuer($issuer['id'], $issuer['name'], $issuer['method']);
            }

            return $issuers;
        }
    }
}
