<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Common\Issuer;
use Omnipay\Tests\TestCase;

class FetchIssuersRequestTest extends TestCase
{
    /**
     * @var \Omnipay\Mollie\Message\FetchIssuersRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new FetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
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
        $this->setMockHttpResponse('FetchIssuersSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchIssuersResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $expectedIssuer = new Issuer('ideal_TESTNL99', 'TBM Bank', 'ideal');
        $this->assertEquals(array($expectedIssuer), $response->getIssuers());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchIssuersFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchIssuersResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Unauthorized request', $response->getMessage());
        $this->assertNull($response->getIssuers());
    }
}
