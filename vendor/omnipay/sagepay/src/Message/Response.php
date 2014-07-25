<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Sage Pay Response
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $this->decode($data);
    }

    public function isSuccessful()
    {
        return isset($this->data['Status']) && 'OK' === $this->data['Status'];
    }

    public function isRedirect()
    {
        return isset($this->data['Status']) && '3DAUTH' === $this->data['Status'];
    }

    /**
     * Gateway Reference
     *
     * Unfortunately Sage Pay requires the original VendorTxCode as well as 3 separate
     * fields from the response object to capture or refund transactions at a later date.
     *
     * Active Merchant solves this dilemma by returning the gateway reference in the following
     * custom format: VendorTxCode;VPSTxId;TxAuthNo;SecurityKey
     *
     * We have opted to return this reference as JSON, as the keys are much more explicit.
     */
    public function getTransactionReference()
    {
        $reference = array();
        $reference['VendorTxCode'] = $this->getRequest()->getTransactionId();

        foreach (array('SecurityKey', 'TxAuthNo', 'VPSTxId') as $key) {
            if (isset($this->data[$key])) {
                $reference[$key] = $this->data[$key];
            }
        }

        ksort($reference);

        return json_encode($reference);
    }

    public function getMessage()
    {
        return isset($this->data['StatusDetail']) ? $this->data['StatusDetail'] : null;
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->data['ACSURL'];
        }
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        if ($this->isRedirect()) {
            return array(
                'PaReq' => $this->data['PAReq'],
                'TermUrl' => $this->getRequest()->getReturnUrl(),
                'MD' => $this->data['MD'],
            );
        }
    }

    /**
     * Decode raw ini-style response body
     *
     * @param string The raw response body
     * @return array
     */
    protected function decode($response)
    {
        $lines = explode("\n", $response);
        $data = array();

        foreach ($lines as $line) {
            $line = explode('=', $line, 2);
            if (!empty($line[0])) {
                $data[trim($line[0])] = isset($line[1]) ? trim($line[1]) : '';
            }
        }

        return $data;
    }
}
