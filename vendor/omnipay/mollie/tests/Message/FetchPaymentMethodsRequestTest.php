<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Common\PaymentMethod;
use Omnipay\Tests\TestCase;

class FetchPaymentMethodsRequestTest extends TestCase
{
    /**
     * @var \Omnipay\Mollie\Message\FetchPaymentMethodsRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'mykey'
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEmpty($data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchPaymentMethodsResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $paymentMethods = $response->getPaymentMethods();
        $this->assertCount(4, $paymentMethods);

        $expectedPaymentMethod = new PaymentMethod('ideal', 'iDEAL');

        $this->assertEquals($expectedPaymentMethod, $paymentMethods[0]);
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchPaymentMethodsResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Unauthorized request', $response->getMessage());
        $this->assertNull($response->getPaymentMethods());
    }
}
