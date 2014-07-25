<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Tests\TestCase;

class ServerAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new ServerAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'transactionId' => '123',
                'card' => $this->getValidCard(),
            )
        );
    }

    public function testProfile()
    {
        $this->assertSame($this->request, $this->request->setProfile('NORMAL'));
        $this->assertSame('NORMAL', $this->request->getProfile());
    }

    public function getData()
    {
        $data = $this->request->getData();

        $this->assertSame('https://www.example.com/return', $data['NotificationURL']);
        $this->assertSame('LOW', $data['Profile']);
    }
}
