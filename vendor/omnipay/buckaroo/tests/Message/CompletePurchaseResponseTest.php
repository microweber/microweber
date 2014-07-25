<?php

namespace Omnipay\Buckaroo\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testSuccess()
    {
        $data = array(
            'BRQ_STATUSCODE' => '190',
            'BRQ_STATUSMESSAGE' => 'hi!',
            'BRQ_PAYMENT' => '5',
        );

        $response = new CompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('190', $response->getCode());
        $this->assertSame('hi!', $response->getMessage());
        $this->assertSame('5', $response->getTransactionReference());
    }

    public function testEmpty()
    {
        $response = new CompletePurchaseResponse($this->getMockRequest(), array());

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getTransactionReference());
    }
}
