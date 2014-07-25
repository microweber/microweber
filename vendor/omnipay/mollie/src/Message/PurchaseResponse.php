<?php

namespace Omnipay\Mollie\Message;

class PurchaseResponse extends FetchTransactionResponse
{
    /**
     * When you do a `purchase` the request is never succesful because
     * you need to redirect off-site to complete the purchase.
     *
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }
}
