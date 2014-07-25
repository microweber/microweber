<?php

namespace Omnipay\Coinbase\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = m::mock('\Omnipay\Coinbase\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    public function testGenerateNonce()
    {
        // nonce should increment every time
        $n1 = $this->request->generateNonce();
        $n2 = $this->request->generateNonce();

        $this->assertGreaterThan($n1, $n2);
    }

    public function testGenerateSignature()
    {
        $url = 'exampleurl';
        $body = 'examplebody';
        $nonce = '12345';
        $this->request->setSecret('shhh');

        $expected = hash_hmac('sha256', '12345exampleurlexamplebody', 'shhh');
        $this->assertSame($expected, $this->request->generateSignature($url, $body, $nonce));
    }
}
