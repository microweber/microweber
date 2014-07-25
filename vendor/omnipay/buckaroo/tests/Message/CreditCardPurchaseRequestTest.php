<?php

namespace Omnipay\Buckaroo\Message;

use Omnipay\Tests\TestCase;

class CreditCardPurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CreditCardPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
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
        $data = $this->request->getData();

        $this->assertSame('visa', $data['Brq_payment_method']);
    }
}
