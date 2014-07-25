<?php

namespace Omnipay\Buckaroo;

use Omnipay\Tests\GatewayTestCase;

class IdealGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new IdealGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Buckaroo\Message\IdealPurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
