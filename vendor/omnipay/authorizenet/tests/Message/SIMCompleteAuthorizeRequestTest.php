<?php

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\Tests\TestCase;

class SIMCompleteAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new SIMCompleteAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage Incorrect hash
     */
    public function testGetDataInvalid()
    {
        $this->getHttpRequest()->request->replace(array('x_MD5_Hash' => 'invalid'));
        $this->request->getData();
    }

    public function testGetHash()
    {
        $this->assertSame(md5(''), $this->request->getHash());

        $this->request->setHashSecret('hashsec');
        $this->request->setApiLoginId('apilogin');
        $this->request->setTransactionId('trnid');
        $this->request->setAmount('10.00');

        $this->assertSame(md5('hashsecapilogintrnid10.00'), $this->request->getHash());
    }

    public function testSend()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'x_response_code' => '1',
                'x_trans_id' => '12345',
                'x_MD5_Hash' => md5('shhhuser9910.00'),
            )
        );
        $this->request->setApiLoginId('user');
        $this->request->setHashSecret('shhh');
        $this->request->setAmount('10.00');
        $this->request->setTransactionId(99);

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
