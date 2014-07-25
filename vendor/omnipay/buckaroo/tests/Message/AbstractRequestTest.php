<?php

namespace Omnipay\Buckaroo\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = m::mock('\Omnipay\Buckaroo\Message\AbstractRequest')->makePartial();
        $this->request->initialize(
            array(
                'websiteKey' => 'web',
                'secretKey' => 'secret',
                'amount' => '12.00',
                'returnUrl' => 'https://www.example.com/return',
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(array(
            'websiteKey' => 'web',
            'secretKey' => 'secret',
            'amount' => '12.00',
            'currency' => 'EUR',
            'testMode' => true,
            'transactionId' => 13,
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
        ));

        $data = $this->request->getData();

        $this->assertSame('web', $data['Brq_websitekey']);
        $this->assertSame('12.00', $data['Brq_amount']);
        $this->assertSame('EUR', $data['Brq_currency']);
        $this->assertSame(13, $data['Brq_invoicenumber']);
        $this->assertSame('https://www.example.com/return', $data['Brq_return']);
        $this->assertSame('https://www.example.com/cancel', $data['Brq_returncancel']);
    }

    public function testGenerateSignature()
    {
        $this->request->setSecretKey('secret');
        $data = array(
            'Brq_websitekey' => 'a',
            'Brq_amount' => 'b',
            'Brq_signature' => 'ignore',
        );

        $expected = sha1('Brq_amount=bBrq_websitekey=asecret');
        $this->assertSame($expected, $this->request->generateSignature($data));
    }

    public function testGenerateSignatureCaseInsensitivity()
    {
        $this->request->setSecretKey('secret');
        $data = array(
            'Brq_websitekey' => 'a',
            'Brq_amount' => 'b',
            'BrQ_SIgnatURE' => 'ignore',
        );

        $expected = sha1('Brq_amount=bBrq_websitekey=asecret');
        $this->assertSame($expected, $this->request->generateSignature($data));
    }

    public function testSend()
    {
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame('https://checkout.buckaroo.nl/html/', $response->getRedirectUrl());

        $data = $response->getRedirectData();
        $this->assertSame('web', $data['Brq_websitekey']);
        $this->assertArrayHasKey('Brq_signature', $data);
    }

    public function testGetEndpoint()
    {
        $this->request->setTestMode(false);
        $this->assertStringStartsWith('https://checkout.buckaroo.nl', $this->request->getEndpoint());

        $this->request->setTestMode(true);
        $this->assertStringStartsWith('https://testcheckout.buckaroo.nl', $this->request->getEndpoint());
    }
}
