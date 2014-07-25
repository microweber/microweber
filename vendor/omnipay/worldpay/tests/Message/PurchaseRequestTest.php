<?php

namespace Omnipay\WorldPay\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'returnUrl' => 'https://example.com/return',
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(
            array(
                'installationId' => 'id1',
                'accountId' => 'id2',
                'transactionId' => 'id3',
                'description' => 'food',
                'amount' => '12.00',
                'currency' => 'GBP',
                'returnUrl' => 'https://example.com/return',
            )
        );

        $data = $this->request->getData();

        $this->assertSame('id1', $data['instId']);
        $this->assertSame('id2', $data['accId1']);
        $this->assertSame('id3', $data['cartId']);
        $this->assertSame('food', $data['desc']);
        $this->assertSame('12.00', $data['amount']);
        $this->assertSame('GBP', $data['currency']);
        $this->assertSame(0, $data['testMode']);
        $this->assertSame('https://example.com/return', $data['MC_callback']);
    }

    public function testGetDataTestMode()
    {
        $this->request->setTestMode(true);

        $data = $this->request->getData();

        $this->assertSame(100, $data['testMode']);
    }
}
