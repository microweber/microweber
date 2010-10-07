<?php
require_once MIO_PATH . 'StreamFactory.php';

class MioStreamFactoryTest extends UnitTestCase
{
    private
        $factory;
    public function setUp()
    {
        $this->factory = new MioStreamFactory();
    }
    public function tearDown()
    {
        unset( $this->factory );
    }
    public function testCreatingServerSocket()
    {
        $stream = $this->factory->createServerStream( '127.0.0.1:8888' );
        if( $stream instanceof MioStream ) {
            $this->pass();
        }
    }
    public function testCreatingFileStream()
    {
        $stream = $this->factory->createFileStream( '/tmp/mytest', 'w+' );
        if( $stream instanceof MioStream ) {
            $this->pass();
        }
    }
    public function testCreatingSocketStream()
    {
        // we must first create something listening
        $server = $this->factory->createServerStream( '127.0.0.1:8888' );
        $stream = $this->factory->createSocketStream( '127.0.0.1', 8888 );
        if( $stream instanceof MioStream ) {
            $this->pass();
        }
    }
}
