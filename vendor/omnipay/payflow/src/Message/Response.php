<?php

namespace Omnipay\Payflow\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Payflow Response
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        if (empty($data)) {
            throw new InvalidResponseException;
        }

        $this->data = $this->decodeData($data);
    }

    /**
     * Decode absurd name value pair format
     */
    public function decodeData($data)
    {
        $output = array();
        while (strlen($data) > 0) {
            preg_match('/(\w+)(\[(\d+)\])?=/', $data, $matches);
            $key = $matches[1];
            $data = substr($data, strlen($matches[0]));

            if (isset($matches[3])) {
                $value = substr($data, 0, $matches[3]);
            } else {
                $next = strpos($data, '&');
                $value = $next === false ? $data : substr($data, 0, $next);
            }

            $data = substr($data, strlen($value) + 1);
            $output[$key] = $value;
        }

        return $output;
    }

    public function isSuccessful()
    {
        return isset($this->data['RESULT']) && '0' === $this->data['RESULT'];
    }

    public function getTransactionReference()
    {
        return isset($this->data['PNREF']) ? $this->data['PNREF'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['RESPMSG']) ? $this->data['RESPMSG'] : null;
    }
}
