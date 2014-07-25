<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var \Omnipay\Mollie\Message\PurchaseRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey'      => 'mykey',
            'amount'      => '12.00',
            'issuer'      => 'my bank',
            'description' => 'Description',
            'returnUrl'   => 'https://www.example.com/return',
            'method'      => 'ideal',
            'metadata'    => 'meta',
        ));
    }

    public function testGetData()
    {
        $this->request->initialize(array(
            'apiKey'        => 'mykey',
            'amount'        => '12.00',
            'description'   => 'Description',
            'returnUrl'     => 'https://www.example.com/return',
            'paymentMethod' => 'ideal',
            'metadata'      => 'meta',
            'issuer'        => 'my bank',
        ));

        $data = $this->request->getData();

        $this->assertSame("12.00", $data['amount']);
        $this->assertSame('Description', $data['description']);
        $this->assertSame('https://www.example.com/return', $data['redirectUrl']);
        $this->assertSame('ideal', $data['method']);
        $this->assertSame('meta', $data['metadata']);
        $this->assertSame('my bank', $data['issuer']);
        $this->assertCount(6, $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\PurchaseResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertSame('https://www.mollie.nl/payscreen/pay/Qzin4iTWrU', $response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('tr_Qzin4iTWrU', $response->getTransactionReference());
        $this->assertTrue($response->isOpen());
        $this->assertFalse($response->isPaid());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\PurchaseResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame("The issuer is invalid", $response->getMessage());
    }
}
