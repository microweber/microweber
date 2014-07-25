<?php

namespace Omnipay\PayPal\Message;

use Omnipay\PayPal\Message\FetchTransactionRequest;
use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    /**
     * @var \Omnipay\PayPal\Message\FetchTransactionRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();

        $request = $this->getHttpRequest();

        $this->request = new FetchTransactionRequest($client, $request);
    }

    public function testGetData()
    {
        $this->request->setTransactionReference('ABC-123');
        $this->request->setUsername('testuser');
        $this->request->setPassword('testpass');
        $this->request->setSignature('SIG');
        $this->request->setSubject('SUB');

        $expected = array();
        $expected['METHOD'] = 'GetTransactionDetails';
        $expected['TRANSACTIONID'] = 'ABC-123';
        $expected['USER'] = 'testuser';
        $expected['PWD'] = 'testpass';
        $expected['SIGNATURE'] = 'SIG';
        $expected['SUBJECT'] = 'SUB';
        $expected['VERSION'] = RefundRequest::API_VERSION;

        $this->assertEquals($expected, $this->request->getData());
    }
}
