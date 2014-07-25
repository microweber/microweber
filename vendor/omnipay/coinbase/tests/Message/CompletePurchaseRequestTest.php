<?php

namespace Omnipay\Coinbase\Message;

use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->httpRequest = $this->getHttpRequest();
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->httpRequest);
        $this->request->initialize(
            array(
                'apiKey' => 'abc123',
                'secret' => 'shhh',
            )
        );
    }

    public function testGetDataGet()
    {
        $this->httpRequest->query->replace(
            array('order' => array('id' => '9XMWP4YG'))
        );

        $data = $this->request->getData();
        $this->assertSame('9XMWP4YG', $data['id']);
    }

    public function testGetDataPost()
    {
        // post data is sent as JSON
        $content = '{"order":{"id":"9XMWP4YG","created_at":"2014-05-11T22:15:41-07:00","status":"completed"}}';
        $this->httpRequest = new HttpRequest(array(), array(), array(), array(), array(), array(), $content);
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->httpRequest);
        $this->request->initialize(
            array(
                'apiKey' => 'abc123',
                'secret' => 'shhh',
            )
        );

        $data = $this->request->getData();
        $this->assertSame('9XMWP4YG', $data['id']);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage Missing Order ID
     */
    public function testGetDataInvalid()
    {
        $this->request->getData();
    }

    public function testSendSuccess()
    {
        $this->httpRequest->query->replace(
            array('order' => array('id' => '9XMWP4YG'))
        );
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('completed', $response->getMessage());
        $this->assertSame('9XMWP4YG', $response->getTransactionReference());
    }

    public function testSendFailure()
    {
        $this->httpRequest->query->replace(
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
