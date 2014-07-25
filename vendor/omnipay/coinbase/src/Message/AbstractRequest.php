<?php

namespace Omnipay\Coinbase\Message;

/**
 * Coinbase Abstract Request
 *
 * @method \Omnipay\Coinbase\Message\Response send()
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $endpoint = 'https://coinbase.com/api/v1';

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    public function sendRequest($method, $action, $data = null)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $nonce = $this->generateNonce();
        $url = $this->endpoint.$action;
        $body = $data ? http_build_query($data) : null;

        $httpRequest = $this->httpClient->createRequest($method, $url, null, $body);
        $httpRequest->setHeader('ACCESS_KEY', $this->getApiKey());
        $httpRequest->setHeader('ACCESS_SIGNATURE', $this->generateSignature($url, $body, $nonce));
        $httpRequest->setHeader('ACCESS_NONCE', $nonce);

        return $httpRequest->send();
    }

    public function generateNonce()
    {
        return sprintf('%0.0f', round(microtime(true) * 1000000));
    }

    public function generateSignature($url, $body, $nonce)
    {
        $message = $nonce.$url.$body;

        return hash_hmac('sha256', $message, $this->getSecret());
    }
}
