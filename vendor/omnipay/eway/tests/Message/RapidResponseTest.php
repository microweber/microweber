<?php

namespace Omnipay\Eway\Message;

use Omnipay\Tests\TestCase;

class RapidResponseTest extends TestCase
{
    public function testGetMessage()
    {
        $data = array('ResponseMessage' => 'A2000');
        $response = new RapidResponse($this->getMockRequest(), $data);

        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testGetMessageMultiple()
    {
        $data = array('ResponseMessage' => 'V6101,V6102');
        $response = new RapidResponse($this->getMockRequest(), $data);

        $this->assertSame('Invalid EWAY_CARDEXPIRYMONTH, Invalid EWAY_CARDEXPIRYYEAR', $response->getMessage());
    }
}
