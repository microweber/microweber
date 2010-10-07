<?php
require_once MIO_PATH . 'Selector.php';

class MioSelectorTest extends UnitTestCase
{
    private
        $server,
        $selector,
        $stream;

    public function setUp()
    {
        $this->server   = stream_socket_server( '127.0.0.1:8888', $errno=null, $errstr=null );
        if( !$this->server ) {
            throw new Exception("Could not start test server [$errno:$errstr]");
        }
        $this->selector = new MioSelector();
        $this->stream   = new MioStream( fsockopen( '127.0.0.1', 8888, $errno=null, $errstr=null ), '127.0.0.1:8888' );
        $this->selector->register(
            $this->stream,
            MioSelectionKey::OP_WRITE
        );
    }

    public function tearDown()
    {
        if( is_resource( $this->server ) ) {
            fclose( $this->server );
        }
        $this->selector->close();
    }

    public function testPropertyAccess()
    {
        try {
            $this->selector->selection_keys;
            $this->pass();
        } catch( InvalidArgumentException $e ) {
            $this->fail();
        }
        try {
            $this->selector->open;
            $this->fail();
        } catch( InvalidArgumentException $e ) {
            $this->pass();
        }
    }

    public function testRegister()
    {
        $this->assertTrue(
            is_array( $this->selector->selection_keys )
        );
        $this->assert(
            new IdenticalExpectation( $this->stream ),
            $this->selector->selection_keys[0]->stream
        );
    }

    public function testRegisterClosedStream()
    {
        $this->selector->removeKey(
            $this->selector->keyFor( $this->stream )
        );
        $this->stream->close();
        try {
            $this->selector->register(
                $this->stream,
                MioSelectionKey::OP_WRITE
            );
            $this->fail();
        } catch( MioClosedException $e ) {
            $this->pass();
        }
    }

    public function testRegisterClosedSelector()
    {
        $this->selector->close();
        try {
            $this->selector->register(
                $this->stream,
                MioSelectionKey::OP_WRITE
            );
            $this->fail();
        } catch( MioClosedException $e ) {
            $this->pass();
        }
    }

    public function testClosing()
    {
        $this->assertTrue(
            $this->selector->isOpen()
        );
        $this->assertTrue(
            $this->stream->isOpen()
        );
        $this->selector->close();
        $this->assertFalse(
            $this->selector->isOpen()
        );
        $this->assertFalse(
            $this->stream->isOpen()
        );
    }
    
    public function testHasStream()
    {
        $this->assertTrue(
            $this->selector->hasStream( 
                $this->stream
            )
        );
    }

    public function testKeyFor() 
    {
        $key = $this->selector->keyFor( $this->stream );
        $this->assertTrue(
            $key instanceof MioSelectionKey 
        );
        $this->assert(
            new IdenticalExpectation( $this->stream ),
            $key->stream
        );
    }

    public function testRemoveKey()
    {
        // Add a second stream to confirm the other 
        // streams don't get corrupted
        $stream2 = new MioStream( fsockopen( '127.0.0.1', 8888, $errno=null, $errstr=null ), '127.0.0.1:8888' );
        $this->selector->register(
            $stream2, 
            MioSelectionKey::OP_WRITE
        );
        $this->assertTrue(
            $this->selector->hasStream( $this->stream )
        );
        $this->assertTrue(
            $this->selector->hasStream( $stream2 )
        );
        $this->selector->removeKey( 
            $this->selector->keyFor(
                $this->stream
            )
        );
        $this->assertFalse(
            $this->selector->hasStream( $this->stream )
        );
        $this->assertTrue(
            $this->selector->hasStream( $stream2 )
        );
        $this->assertFalse(
            $this->stream->isOpen()
        );
    }

    public function testSelectWhenNothingIsRegistered()
    {
        $this->selector->removeKey(
            $this->selector->keyFor( $this->stream )
        );
        $this->assertFalse(
            $this->selector->select()
        );
    }

    public function testClosedRemovalInSelect()
    {
        $this->stream->close();
        $this->assertFalse(
            $this->selector->select()
        );
    }

    /**
     * this is more of a full start-to-finnish test
     */
    public function testSelect()
    {
        $data = 'Some test data to send';
        $con = stream_socket_accept( $this->server );
        $this->assertEqual(
            $this->selector->select(),
            1
        );
        $key = $this->selector->selected_keys[0];
        $this->assertTrue(
            $key->isWritable()
        );
        $key->stream->write( $data );
        fread( $con, 1024 );

        $key->setInterestOps( MioSelectionKey::OP_READ );

        $this->assertEqual(
            $this->selector->select(),
            0
        );
        
        fwrite( $con, $data );

        $this->assertEqual(
            $this->selector->select(),
            1
        );
    }

    public function testTurnToString()
    {
        $this->assertPattern(
            '/Selector \(\d:\d\)/',
            "".$this->selector
        );
    }
}
