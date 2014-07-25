<?php

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\Tests\TestCase;

class SIMAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new SIMAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'clientIp' => '10.0.0.1',
                'amount' => '12.00',
                'returnUrl' => 'https://www.example.com/return',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('AUTH_ONLY', $data['x_type']);
        $this->assertSame('PAYMENT_FORM', $data['x_show_form']);
        $this->assertArrayNotHasKey('x_test_request', $data);
    }

    public function testGetDataTestMode()
    {
        $this->request->setTestMode(true);

        $data = $this->request->getData();

        $this->assertSame('TRUE', $data['x_test_request']);
    }

    public function testGetHash()
    {
        $this->request->setApiLoginId('user');
        $this->request->setTransactionKey('key');
        $data = array(
            'x_fp_sequence' => 'a',
            'x_fp_timestamp' => 'b',
            'x_amount' => 'c',
        );

        $expected = hash_hmac('md5', 'user^a^b^c^', 'key');

        $this->assertSame($expected, $this->request->getHash($data));
    }

    public function testSend()
    {
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNotEmpty($response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());

        $redirectData = $response->getRedirectData();
        $this->assertSame('https://www.example.com/return', $redirectData['x_relay_url']);
    }
}
