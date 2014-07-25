<?php

namespace Omnipay\Buckaroo\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'websiteKey' => 'web',
            'transactionId' => 13,
            'secretKey' => 'shhhh',
            'amount' => '12.00',
            'currency' => 'ZAR',
            'testMode' => true,
        ));
        $this->getHttpRequest()->request->replace(array());
    }

    public function testGetData()
    {
        $this->getHttpRequest()->request->set('Brq_signature', $this->request->generateSignature($this->getHttpRequest()->request->all()));
        $data = $this->request->getData();

        $this->assertSame(array_change_key_case($this->getHttpRequest()->request->all(), CASE_UPPER), $data);
    }

    public function testGetDataSignatureKeyCaseInsensitivity()
    {
        $this->getHttpRequest()->request->set('Brq_SignATure', $this->request->generateSignature($this->getHttpRequest()->request->all()));
        $data = $this->request->getData();

        $this->assertArrayHasKey('BRQ_SIGNATURE', $data);
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage Incorrect signature
     */
    public function testGetDataInvalidSignature()
    {
        $this->getHttpRequest()->request->set('Brq_signature', 'zzz');

        $this->request->getData();
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage Incorrect signature
     */
    public function testGetDataMissingSignature()
    {
        $this->getHttpRequest()->request->replace(array());

        $this->request->getData();
    }

    public function testSendSuccess()
    {
        $this->getHttpRequest()->request->set('Brq_payment', '5');
        $this->getHttpRequest()->request->set('Brq_statuscode', '190');
        $this->getHttpRequest()->request->set('Brq_statusmessage', 'msg');
        $this->getHttpRequest()->request->set('Brq_signature', $this->request->generateSignature($this->getHttpRequest()->request->all()));
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('5', $response->getTransactionReference());
        $this->assertSame('190', $response->getCode());
        $this->assertSame('msg', $response->getMessage());
    }

    public function testSendError()
    {
        $this->getHttpRequest()->request->set('Brq_payment', '5');
        $this->getHttpRequest()->request->set('Brq_statuscode', '999');
        $this->getHttpRequest()->request->set('Brq_statusmessage', 'msg');
        $this->getHttpRequest()->request->set('Brq_signature', $this->request->generateSignature($this->getHttpRequest()->request->all()));
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('5', $response->getTransactionReference());
        $this->assertSame('999', $response->getCode());
        $this->assertSame('msg', $response->getMessage());
    }
}
