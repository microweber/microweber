<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class ProPurchaseRequestTest extends TestCase
{
    /**
     * @var ProPurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new ProPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'USD',
                'card' => $this->getValidCard(),
            )
        );
    }

    public function testGetData()
    {
        $card = new CreditCard($this->getValidCard());
        $card->setStartMonth(1);
        $card->setStartYear(2000);

        $this->request->setCard($card);
        $this->request->setTransactionId('abc123');
        $this->request->setDescription('Sheep');
        $this->request->setClientIp('127.0.0.1');

        $data = $this->request->getData();

        $this->assertSame('DoDirectPayment', $data['METHOD']);
        $this->assertSame('Sale', $data['PAYMENTACTION']);
        $this->assertSame('10.00', $data['AMT']);
        $this->assertSame('USD', $data['CURRENCYCODE']);
        $this->assertSame('abc123', $data['INVNUM']);
        $this->assertSame('Sheep', $data['DESC']);
        $this->assertSame('127.0.0.1', $data['IPADDRESS']);

        $this->assertSame($card->getNumber(), $data['ACCT']);
        $this->assertSame($card->getBrand(), $data['CREDITCARDTYPE']);
        $this->assertSame($card->getExpiryDate('mY'), $data['EXPDATE']);
        $this->assertSame('012000', $data['STARTDATE']);
        $this->assertSame($card->getCvv(), $data['CVV2']);
        $this->assertSame($card->getIssueNumber(), $data['ISSUENUMBER']);

        $this->assertSame($card->getFirstName(), $data['FIRSTNAME']);
        $this->assertSame($card->getLastName(), $data['LASTNAME']);
        $this->assertSame($card->getEmail(), $data['EMAIL']);
        $this->assertSame($card->getAddress1(), $data['STREET']);
        $this->assertSame($card->getAddress2(), $data['STREET2']);
        $this->assertSame($card->getCity(), $data['CITY']);
        $this->assertSame($card->getState(), $data['STATE']);
        $this->assertSame($card->getPostcode(), $data['ZIP']);
        $this->assertSame($card->getCountry(), $data['COUNTRYCODE']);
    }
}
