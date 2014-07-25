<?php

namespace Omnipay\Stripe\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = m::mock('\Omnipay\Stripe\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    public function testCardToken()
    {
        $this->assertSame($this->request, $this->request->setCardToken('abc123'));
        $this->assertSame('abc123', $this->request->getCardToken());
        $this->assertSame('abc123', $this->request->getToken());
    }

    public function testMetadata()
    {
        $this->assertSame($this->request, $this->request->setMetadata(array('foo' => 'bar')));
        $this->assertSame(array('foo' => 'bar'), $this->request->getMetadata());
    }
}
