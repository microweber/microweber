<?php
require_once MIO_PATH . 'Stream.php';
require_once MIO_PATH . 'Exception.php';

class MioStreamTest extends UnitTestCase
{
    private
        $server,
        $stream;

    public function setUp()
    {
        $this->server = stream_socket_server( '127.0.0.1:8888', $errno=null, $errstr=null );
        if( !$this->server ) {
            throw new Exception("Could not start test server [$errno:$errstr]");
        }
        $this->stream = new MioStream( fsockopen( '127.0.0.1', 8888, $errno=null, $errstr=null ), '127.0.0.1:8888' );
    }

    public function tearDown()
    {
        if( is_resource( $this->server ) ) {
            fclose( $this->server );
        }
        $this->stream->close();
        unset( $this->server, $this->stream );
    }

    public function accept()
    {
        return stream_socket_accept( $this->server );
    }

    public function testCreateWithBadStream()
    {
        try {
            new MioStream( null, 'fail me' );
            $this->fail();
        } catch( MioException $e ) {
            $this->pass();
        }
    }

    public function testBlocking()
    {
        $this->assertFalse(
            $this->stream->isBlocking(),
            'Stream should start off in non-blocking mode'
        );
        $this->stream->setBlocking( 1 );
        $this->assertTrue(
            $this->stream->isBlocking()
        );
    }

    public function testBadBlocking()
    {
        try {
            $this->stream->setBlocking( 2 );
            $this->fail();
        } catch( MioBlockingException $e ) {
            $this->pass();
        }
    }

    public function testOpen()
    {
        $this->assertTrue(
            $this->stream->isOpen(),
            'Stream should start off open'
        );
    }

    public function testClose()
    {
        $this->stream->close();
        $this->assertFalse(
            $this->stream->isOpen(),
            'Stream should be marked as closed when it is closed'
        );
    }

    public function testRead()
    {
        $con = $this->accept();
        fwrite( $con, "hello" );
        $this->assertEqual(
            $this->stream->read( 1024 ),
            'hello'
        );
    }

    public function testReadClosed()
    {
        $con = $this->accept();
        fwrite( $con, "hello" );
        $this->stream->close();
        try {
            $this->stream->read( 1024 );
            $this->fail();
        } catch( MioClosedException $e ) { 
            $this->pass();
        }
    }

    public function testWrite()
    {
        $con = $this->accept();
        $string = "hello";
        $written = $this->stream->write( $string );
        $this->assertEqual(
            fread( $con, 1024 ),
            $string
        );
        $this->assertEqual(
            $written,
            strlen( $string )
        );
    }

    public function testWriteClosed()
    {
        $this->stream->close();
        try {
            $this->stream->write( 'hello' );
            $this->fail();
        } catch( MioClosedException $e ) {
            $this->pass();
        }
    }

    public function testWriteOveriteFwriteBuffer()
    {
        $string = 'hello';
        $string = str_repeat( $string, 13464 );
        $string_len = strlen( $string );

        $con = $this->accept();
        $this->assertFalse(
            $this->stream->write( $string )
        );

        // Clear out the stream buffer
        fread( $con, $string_len );
        
        $this->assertTrue(
            $this->stream->write()
        );
    }

    public function testWriteExceedStreamBuffer()
    {
        $string = 'hello';
        $string = str_repeat( $string, 1000000 );
        $string_len = strlen( $string );

        $con = $this->accept();
        try {
            $this->stream->write( $string );
            $this->fail();
        } catch( MioException $e ) {
            $this->pass();
        }
    }

    public function testAccept()
    {
        $stream = new MioStream( $this->server, '127.0.0.1:8888' );
        $this->assertTrue(
            $stream->accept() instanceof MioStream
        );
    }

}


