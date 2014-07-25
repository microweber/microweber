<?php

namespace Omnipay\Buckaroo;

use Omnipay\Tests\GatewayTestCase;

class PayPalGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PayPalGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Buckaroo\Message\PayPalPurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
