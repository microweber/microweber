<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    /**
     * @var \Omnipay\Mollie\Message\FetchTransactionRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey',
            'transactionReference' => 'tr_Qzin4iTWrU',
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame("tr_Qzin4iTWrU", $data['id']);
        $this->assertCount(1, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchTransactionResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isPaid());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPaidOut());
        $this->assertFalse($response->isRedirect());
        $this->assertSame("paid", $response->getStatus());
        $this->assertSame('tr_Qzin4iTWrU', $response->getTransactionReference());
        $this->assertSame("100.00", $response->getAmount());
    }

    public function testSendExpired()
    {
        $this->setMockHttpResponse('FetchTransactionExpired.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchTransactionResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('tr_Qzin4iTWrU', $response->getTransactionReference());
        $this->assertTrue($response->isExpired());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchTransactionFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchTransactionResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getStatus());
        $this->assertNull($response->getAmount());
    }
}
