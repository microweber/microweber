<?php

namespace Omnipay\Coinbase\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    public function setUp()
    {
        $this->httpRequest = $this->getHttpRequest();
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->httpRequest);
        $this->request->initialize(
            array(
                'transactionReference' => '9XMWP4YG',
                'apiKey' => 'abc123',
                'secret' => 'shhh',
            )
        );
    }

    public function testGetDataGet()
    {
        $this->request->initialize(array('transactionReference' => 'abc123'));

        $data = $this->request->getData();
        $this->assertSame('abc123', $data['id']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('completed', $response->getMessage());
        $this->assertSame('9XMWP4YG', $response->getTransactionReference());
    }

    public function testSendFailure()
    {
        $this->httpRequest->request->replace(
            array('order' => array('id' => '9XMWP4YG'))
        );
        $this->setMockHttpResponse('FetchTransactionFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Order not found with that id', $response->getMessage());
        $this->assertNull($response->getTransactionReference());
    }
}
