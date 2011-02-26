<?php

require_once 'Sabre/HTTP/ResponseMock.php';

class Sabre_HTTP_AWSAuthTest extends PHPUnit_Framework_TestCase {

    private $response;
    private $request;
    private $basicAuth;

    const REALM = 'SabreDAV unittest';

    public function setUp() {

        $this->response = new Sabre_HTTP_ResponseMock();
        $this->auth = new Sabre_HTTP_AWSAuth();
        $this->auth->setRealm(self::REALM);
        $this->auth->setHTTPResponse($this->response);

    }

    public function testNoHeader() {

        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD' => 'GET',
        ));
        
        $this->auth->setHTTPRequest($request);
        
        $result = $this->auth->init();

        $this->assertFalse($result,'No AWS Authorization header was supplied, so we should have gotten false');
        $this->assertEquals(Sabre_HTTP_AWSAuth::ERR_NOAWSHEADER,$this->auth->errorCode);

    }

    public function testIncorrectContentMD5() {

        $accessKey = 'accessKey';
        $secretKey = 'secretKey';

        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD'      => 'GET',
            'HTTP_AUTHORIZATION'  => "AWS $accessKey:sig",
            'HTTP_CONTENT_MD5'    => 'garbage',
            'REQUEST_URI'         => '/',
        ));
        
        $this->auth->setHTTPRequest($request);
        $this->auth->init();
        $result = $this->auth->validate($secretKey);

        $this->assertFalse($result);
        $this->assertEquals(Sabre_HTTP_AWSAuth::ERR_MD5CHECKSUMWRONG,$this->auth->errorCode);

    }

    public function testNoDate() {

        $accessKey = 'accessKey';
        $secretKey = 'secretKey';
        $content = 'thisisthebody';
        $contentMD5 = base64_encode(md5($content,true)); 


        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD'      => 'POST',
            'HTTP_AUTHORIZATION'  => "AWS $accessKey:sig",
            'HTTP_CONTENT_MD5'    => $contentMD5,
        ));

        $request->setBody($content);
        
        $this->auth->setHTTPRequest($request);
        $this->auth->init();
        $result = $this->auth->validate($secretKey);

        $this->assertFalse($result);
        $this->assertEquals(Sabre_HTTP_AWSAuth::ERR_INVALIDDATEFORMAT,$this->auth->errorCode);

    }

    public function testFutureDate() {

        $accessKey = 'accessKey';
        $secretKey = 'secretKey';
        $content = 'thisisthebody';
        $contentMD5 = base64_encode(md5($content,true)); 

        $date = new DateTime('@' . time() + (60*20));
        $date->setTimeZone(new DateTimeZone('GMT'));
        $date = $date->format('D, d M Y H:i:s \\G\\M\\T');

        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD'      => 'POST',
            'HTTP_AUTHORIZATION'  => "AWS $accessKey:sig",
            'HTTP_CONTENT_MD5'    => $contentMD5,
            'HTTP_DATE'           => $date,
        ));

        $request->setBody($content);
        
        $this->auth->setHTTPRequest($request);
        $this->auth->init();
        $result = $this->auth->validate($secretKey);

        $this->assertFalse($result);
        $this->assertEquals(Sabre_HTTP_AWSAuth::ERR_REQUESTTIMESKEWED,$this->auth->errorCode);

    }

    public function testPastDate() {

        $accessKey = 'accessKey';
        $secretKey = 'secretKey';
        $content = 'thisisthebody';
        $contentMD5 = base64_encode(md5($content,true)); 

        $date = new DateTime('@' . time() - (60*20));
        $date->setTimeZone(new DateTimeZone('GMT'));
        $date = $date->format('D, d M Y H:i:s \\G\\M\\T');

        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD'      => 'POST',
            'HTTP_AUTHORIZATION'  => "AWS $accessKey:sig",
            'HTTP_CONTENT_MD5'    => $contentMD5,
            'HTTP_X_AMZ_DATE'     => $date,
        ));

        $request->setBody($content);
        
        $this->auth->setHTTPRequest($request);
        $this->auth->init();
        $result = $this->auth->validate($secretKey);

        $this->assertFalse($result);
        $this->assertEquals(Sabre_HTTP_AWSAuth::ERR_REQUESTTIMESKEWED,$this->auth->errorCode);

    }

    public function testIncorrectSignature() {

        $accessKey = 'accessKey';
        $secretKey = 'secretKey';
        $content = 'thisisthebody';

        $contentMD5 = base64_encode(md5($content,true)); 

        $date = new DateTime('now');
        $date->setTimeZone(new DateTimeZone('GMT'));
        $date = $date->format('D, d M Y H:i:s \\G\\M\\T');

        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD'      => 'POST',
            'HTTP_AUTHORIZATION'  => "AWS $accessKey:sig",
            'HTTP_CONTENT_MD5'    => $contentMD5,
            'HTTP_X_AMZ_DATE'     => $date,
            'REQUEST_URI'         => '/',
        ));

        $request->setBody($content);
        
        $this->auth->setHTTPRequest($request);
        $this->auth->init();
        $result = $this->auth->validate($secretKey);

        $this->assertFalse($result);
        $this->assertEquals(Sabre_HTTP_AWSAuth::ERR_INVALIDSIGNATURE,$this->auth->errorCode);

    }

    public function testValidRequest() {

        $accessKey = 'accessKey';
        $secretKey = 'secretKey';
        $content = 'thisisthebody';
        $contentMD5 = base64_encode(md5($content,true)); 

        $date = new DateTime('now');
        $date->setTimeZone(new DateTimeZone('GMT'));
        $date = $date->format('D, d M Y H:i:s \\G\\M\\T');


        $sig = base64_encode($this->hmacsha1($secretKey,
            "POST\n$contentMD5\n\n$date\nx-amz-date:$date\n/evert"
        ));

        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD'      => 'POST',
            'HTTP_AUTHORIZATION'  => "AWS $accessKey:$sig",
            'HTTP_CONTENT_MD5'    => $contentMD5,
            'HTTP_X_AMZ_DATE'     => $date,
            'REQUEST_URI'         => '/evert',
        ));

        $request->setBody($content);
        
        $this->auth->setHTTPRequest($request);
        $this->auth->init();
        $result = $this->auth->validate($secretKey);

        $this->assertTrue($result,'Signature did not validate, got errorcode ' . $this->auth->errorCode);
        $this->assertEquals($accessKey,$this->auth->getAccessKey());

    }

    public function test401() {

        $this->auth->requireLogin();
        $test = preg_match('/^AWS$/',$this->response->headers['WWW-Authenticate'],$matches);
        $this->assertTrue($test==true,'The WWW-Authenticate response didn\'t match our pattern');

    }

    /**
     * Generates an HMAC-SHA1 signature 
     * 
     * @param string $key 
     * @param string $message 
     * @return string 
     */
    private function hmacsha1($key, $message) {

        $blocksize=64;
        if (strlen($key)>$blocksize)
            $key=pack('H*', sha1($key));
        $key=str_pad($key,$blocksize,chr(0x00));
        $ipad=str_repeat(chr(0x36),$blocksize);
        $opad=str_repeat(chr(0x5c),$blocksize);
        $hmac = pack('H*',sha1(($key^$opad).pack('H*',sha1(($key^$ipad).$message))));
        return $hmac;

    }

}

?>
