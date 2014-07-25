<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Tests\TestCase;

class DirectAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new DirectAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'currency' => 'GBP',
                'transactionId' => '123',
                'card' => $this->getValidCard(),
            )
        );
    }

    public function testGetDataDefaults()
    {
        $data = $this->request->getData();

        $this->assertSame('E', $data['AccountType']);
        $this->assertSame(0, $data['ApplyAVSCV2']);
        $this->assertSame(0, $data['Apply3DSecure']);
    }

    public function testGetData()
    {
        $this->request->setAccountType('M');
        $this->request->setApplyAVSCV2(2);
        $this->request->setApply3DSecure(3);
        $this->request->setDescription('food');
        $this->request->setClientIp('127.0.0.1');

        $data = $this->request->getData();

        $this->assertSame('M', $data['AccountType']);
        $this->assertSame('food', $data['Description']);
        $this->assertSame('12.00', $data['Amount']);
        $this->assertSame('GBP', $data['Currency']);
        $this->assertSame('123', $data['VendorTxCode']);
        $this->assertSame('127.0.0.1', $data['ClientIPAddress']);
        $this->assertSame(2, $data['ApplyAVSCV2']);
        $this->assertSame(3, $data['Apply3DSecure']);
    }

    public function testGetDataCustomerDetails()
    {
        $card = $this->request->getCard();
        $data = $this->request->getData();

        $this->assertSame($card->getFirstName(), $data['BillingFirstnames']);
        $this->assertSame($card->getLastName(), $data['BillingSurname']);
        $this->assertSame($card->getBillingAddress1(), $data['BillingAddress1']);
        $this->assertSame($card->getBillingAddress2(), $data['BillingAddress2']);
        $this->assertSame($card->getBillingCity(), $data['BillingCity']);
        $this->assertSame($card->getBillingPostcode(), $data['BillingPostCode']);
        $this->assertSame($card->getBillingState(), $data['BillingState']);
        $this->assertSame($card->getBillingCountry(), $data['BillingCountry']);
        $this->assertSame($card->getBillingPhone(), $data['BillingPhone']);

        $this->assertSame($card->getFirstName(), $data['DeliveryFirstnames']);
        $this->assertSame($card->getLastName(), $data['DeliverySurname']);
        $this->assertSame($card->getShippingAddress1(), $data['DeliveryAddress1']);
        $this->assertSame($card->getShippingAddress2(), $data['DeliveryAddress2']);
        $this->assertSame($card->getShippingCity(), $data['DeliveryCity']);
        $this->assertSame($card->getShippingPostcode(), $data['DeliveryPostCode']);
        $this->assertSame($card->getShippingState(), $data['DeliveryState']);
        $this->assertSame($card->getShippingCountry(), $data['DeliveryCountry']);
        $this->assertSame($card->getShippingPhone(), $data['DeliveryPhone']);
    }

    public function testGetDataCustomerDetailsIgnoresStateOutsideUS()
    {
        $card = $this->request->getCard();
        $card->setBillingCountry('UK');
        $card->setShippingCountry('NZ');

        $data = $this->request->getData();

        $this->assertNull($data['BillingState']);
        $this->assertNull($data['DeliveryState']);
    }

    public function testGetDataVisa()
    {
        $this->request->getCard()->setNumber('4929000000006');
        $data = $this->request->getData();

        $this->assertSame('visa', $data['CardType']);
    }

    public function testGetDataMastercard()
    {
        $this->request->getCard()->setNumber('5404000000000001');
        $data = $this->request->getData();

        $this->assertSame('mc', $data['CardType']);
    }

    public function testGetDataDinersClub()
    {
        $this->request->getCard()->setNumber('30569309025904');
        $data = $this->request->getData();

        $this->assertSame('dc', $data['CardType']);
    }
}
