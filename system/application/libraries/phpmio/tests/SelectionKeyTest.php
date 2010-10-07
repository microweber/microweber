<?php
require_once MIO_PATH . 'SelectionKey.php';

class MioBlankObject {
}

class MioSelectionKeyTest extends UnitTestCase
{
    private
        $server,
        $key;
    
    public function setUp()
    {
        $this->server = stream_socket_server( '127.0.0.1:8888', $errno=null, $errstr=null );
        if( !$this->server ) {
            throw new Exception("Could not start test server [$errno:$errstr]");
        }
        $stream = new MioStream( fsockopen( '127.0.0.1', 8888, $errno=null, $errstr=null ), '127.0.0.1:8888' );
        $this->key = new MioSelectionKey( $stream, 0 );
    }

    public function tearDown()
    {
        if( is_resource( $this->server ) ) {
            fclose( $this->server );
        }
        $this->key->stream->close();
        unset( $this->server, $this->key );
    }

    public function testIllegalPropertyAccess()
    {
        try {
            $this->key->stream;
            $this->pass();
        } catch( InvalidArgumentException $e ) {
            $this->fail();
        }
        try {
            $this->key->interest_ops;
            $this->fail();
        } catch( InvalidArgumentException $e ) {
            $this->pass();
        }
    }

    public function testBlockingCreation()
    {
        $stream = new MioStream( fsockopen( '127.0.0.1', 8888, $errno=null, $errstr=null ), '127.0.0.1:8888' );
        $stream->setBlocking( 1 );
        try {
            $key = new MioSelectionKey( $stream, 0 );
            $this->fail();
        } catch( MioBlockingException $e ) {
            $this->pass();
        }
    }

    public function testClosedCreation()
    {
        $stream = new MioStream( fsockopen( '127.0.0.1', 8888, $errno=null, $errstr=null ), '127.0.0.1:8888' );
        $stream->close();
        try {
            $key = new MioSelectionKey( $stream, 0 );
            $this->fail();
        } catch( MioClosedException $e ) {
            $this->pass();
        }
    }

    public function testInterestOps()
    {
        $this->assertTrue(
            $this->key->interestedIn( 0 )
        );
        $this->assertFalse(
            $this->key->interestedIn( MioSelectionKey::OP_READ )
        );
    }

    public function testSetInterestOps()
    {
        $this->key->setInterestOps( MioSelectionKey::OP_READ );
        $this->assertTrue(
            $this->key->interestedIn( MioSelectionKey::OP_READ )
        );
        $this->assertFalse(
            $this->key->interestedIn( MioSelectionKey::OP_WRITE )
        );
    }

    public function testSetInterestOpsFailure()
    {
        try {
            $this->key->setInterestOps( 123 );
            $this->fail();
        } catch( MioOpsException $e ) {
            $this->pass();
        }
    }

    public function testAddReadableOp()
    {
        $this->key->setInterestOps( MioSelectionKey::OP_READ );
        $this->key->addReadyOp( MioSelectionKey::OP_READ );
        $this->assertTrue(
            $this->key->isReadable()
        );
    }

    public function testAddWritableOp()
    {
        $this->key->setInterestOps( MioSelectionKey::OP_WRITE );
        $this->key->addReadyOp( MioSelectionKey::OP_WRITE );
        $this->assertTrue(
            $this->key->isWritable()
        );
    }

    public function testAddAcceptableOp()
    {
        $this->key->setInterestOps( MioSelectionKey::OP_ACCEPT );
        $this->key->addReadyOp( MioSelectionKey::OP_ACCEPT );
        $this->assertTrue(
            $this->key->isAcceptable()
        );
    }

    public function testAddBadReadyOp()
    {
        try {
            $this->key->addReadyOp( 22 );
            $this->fail();
        } catch( MioOpsException $e ) {
            $this->pass();
        }
    }

    public function testResetReadyOps()
    {
        $this->key->addReadyOp( MioSelectionKey::OP_READ );
        $this->key->resetReadyOps();
        $this->assertFalse(
            $this->key->isReadable()
        );
    }

    public function testAttach()
    {
        $object = new MioBlankObject();
        $this->key->attach( $object );
        $this->assert(
            new IdenticalExpectation( $object ),
            $this->key->attachment
        );
    }

    public function testAttachNonObject()
    {
        $array = array();
        try {
            $this->key->attach( $array );
            $this->fail();
        } catch( MioException $e ) {
            $this->pass();
        }
    }

    public function testTurnToString()
    {
        $this->assertPattern(
            '/SelectionKey \(\d:\d?\)/',
            "".$this->key
        );
    }
    
}

