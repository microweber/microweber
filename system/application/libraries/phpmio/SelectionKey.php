<?php
/**
 * A selection key represents a relationship between a
 * stream and a selector. It is responsible for keeping
 * track of what is wanted of it's stream, this is represented
 * in the selection key's 'interest ops'. It is also 
 * responsible for keeping track of what operations are
 * currently available on it's stream, this is represented
 * in it's 'ready ops'.
 *
 * For convenience an object can be attached to a selection
 * key (at construction or later) and then retrieved from it
 * at a later date.
 *
 * @author Rob Young <bubblenut@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class MioSelectionKey {
    const OP_READ    = 1;
    const OP_WRITE   = 2;
    const OP_ACCEPT  = 4;

    private
        $stream,
        $interest_ops,
        $ready_ops,
        $attachment;
    
    /**
     * Creates a valid selection key or throws an exception. An 
     * exception will be thrown if the stream is blocking or closed; 
     * the interest ops are invalid or the attachment is present
     * and not an object.
     *
     * @param MioStream $stream
     * @param int $interest_ops
     * @param Object $attachment
     * @throws MioException
     */
    public function __construct( $stream, $interest_ops, $attachment=null ) {
        if( $stream->isBlocking() ) {
            throw new MioBlockingException( "Stream must be in non-blocking mode to be registered" );
        }
        if( !$stream->isOpen() ) {
            throw new MioClosedException( "Stream must be open to be registered" );
        }
        $this->stream       = $stream;
        $this->setInterestOps( $interest_ops );
        $this->attach( $attachment );
    }

    /**
     * Magic method to give read-only access to the selection 
     * key's internal variables
     *
     * @param string $key
     * @return mixed
     */
    public function __get( $key )
    {
        if( 'stream' == $key || 'attachment' == $key ) {
            return $this->$key;
        } else {
            throw new InvalidArgumentException( "Invalid property access" );
        }
    }
    
    /**
     * Set new interest ops for this selection key
     *
     * @param int $interest_ops
     * @throws MioOpsException
     * @return null
     */
    public function setInterestOps( $interest_ops )
    {
        if( !is_int( $interest_ops ) || $interest_ops < 0 || $interest_ops > 7 ) {
            throw new MioOpsException( "Invalid interest ops" );
        }
        $this->interest_ops = $interest_ops;
    }

    /**
     * Determine whether this selection key is interested
     * in a particular operation. Ideally this should be
     * package visible only but that's not possible.
     *
     * @param int $ops
     * @return boolean
     */
    public function interestedIn( $ops )
    {
        return ($this->interest_ops & $ops) > 0 || ($ops===$this->interest_ops);
    }

    /**
     * Resets the current ready ops, this is called at the
     * start of every select operation
     *
     */
    public function resetReadyOps()
    {
        $this->ready_ops = 0;
    }

    /**
     * Adds a status to this keys current ready ops. This
     * refers to what the stream is currently able to
     * do
     *
     * @param int $op
     * @throws MioException
     * @return null
     */
    public function addReadyOp( $op )
    {
        if( !is_int( $op ) || $op < 1 || $op > 7 ) {
            throw new MioOpsException( "Invalid current op" );
        }
        $this->ready_ops |= ( $op & $this->interest_ops );
    }

    /**
     * Convenience method to determine whether this stream can 
     * do the given operation
     *
     * @param $op
     * @return boolean
     */
    private function readyOp( $op )
    {
        return ($this->ready_ops & $op) == $op;
    }

    /**
     * Test whether or not this key's stream is reabable
     *
     * @return boolean
     */
    public function isReadable()
    {
        return $this->readyOp( self::OP_READ );
    }

    /**
     * Test whether or not this key's stream is writable
     *
     * @return boolean
     */
    public function isWritable()
    {
        return $this->readyOp( self::OP_WRITE );
    }

    /**
     * Test whether or not this key's stream is ready to accept
     * a connection
     *
     * @return boolean
     */
    public function isAcceptable()
    {
        return $this->readyOp( self::OP_ACCEPT );
    }
    
    /**
     * Attach a new object to this selection key
     *
     * @param object $attachment
     * @throws MioException
     * @return null
     */
    public function attach( $attachment )
    {
        if( $attachment !== null && !is_object( $attachment ) ) {
            throw new MioException( "Invalid attachment" );
        }
        $this->attachment = $attachment;
    }
    
    public function __toString()
    {
        return 'SelectionKey (' . $this->interest_ops . ':' . $this->ready_ops . ')';
    }
}
