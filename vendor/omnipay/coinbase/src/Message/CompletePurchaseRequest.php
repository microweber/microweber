<?php

namespace Omnipay\Coinbase\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Coinbase Complete Purchase Request
 *
 * @method \Omnipay\Coinbase\Message\Response send()
 */
class CompletePurchaseRequest extends FetchTransactionRequest
{
    public function getData()
    {
        // check GET
        $order = $this->httpRequest->query->get('order');

        // check JSON POST data
        if (!$order && $this->httpRequest->getContent()) {
            $content = json_decode($this->httpRequest->getContent(), true);
            if (isset($content['order'])) {
                $order = $content['order'];
            }
        }

        if (empty($order['id'])) {
            throw new InvalidRequestException('Missing Order ID');
        }

        return array('id' => $order['id']);
    }
}
