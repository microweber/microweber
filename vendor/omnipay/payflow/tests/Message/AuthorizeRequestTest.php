<?php

namespace Omnipay\Payflow\Message;

use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'card' => $this->getValidCard(),
            )
        );
    }

    public function testComment1()
    {
        // comment1 is alias for description
        $this->assertSame($this->request, $this->request->setComment1('foo'));
        $this->assertSame('foo', $this->request->getComment1());
        $this->assertSame('foo', $this->request->getDescription());
    }

    public function testComment2()
    {
        $this->assertSame($this->request, $this->request->setComment2('bar'));
        $this->assertSame('bar', $this->request->getComment2());
    }

    public function testGetData()
    {
        $card = $this->getValidCard();
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'description' => 'things',
                'comment2' => 'more things',
                'card' => $card,
                'transactionId' => '123',
            )
        );

        $data = $this->request->getData();

        $this->assertSame('C', $data['TENDER']);
        $this->assertSame('12.00', $data['AMT']);
        $this->assertSame('things', $data['COMMENT1']);
        $this->assertSame('more things', $data['COMMENT2']);
        $this->assertSame('123', $data['ORDERID']);
    }

    public function testEncodeData()
    {
        $data = array(
            'foo' => 'bar',
            'key' => 'value &= reference',
        );

        $expected = 'foo[3]=bar&key[18]=value &= reference';
        $this->assertSame($expected, $this->request->encodeData($data));
    }
}
