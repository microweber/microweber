<?php
/**
 * A thin wrapper around some of the core language stream 
 * access functions such as fread, fwrite and stream_socket_accept.
 * 
 * @author Rob Young <bubblenut@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class MioStream
{
    private
        $stream,
        $name,
        $blocking_mode=0,
        $buffer = '',
        $buffer_limit = 1048576;

    /**
     * Create a new MioStream from a php stream resource. The
     * stream's blocking status is set to non-blocking for two
     * reasons, this is a multiplexing I/O library (Duh!)
     * and also so that the stream is in a known state.
     *
     * @param resource $stream
     * @throws MioException
     */
    public function __construct( $stream, $name )
    {
        if(!is_resource( $stream )) {
            throw new MioException( "Must be a valid stream" );
        }
        $this->stream = $stream;
        $this->name   = $name;
        $this->setBlocking( 0 );
    }

    /**
     * Set the blocking status of the stream
     *
     * @param int $mode
     * @return boolean
     */
    public function setBlocking( $mode )
    {
        if( $mode != 0 && $mode != 1 ) {
            throw new MioBlockingException( "Invalid blocking mode ");
        }
        $this->blocking_mode = $mode;
        return stream_set_blocking( $this->stream, $mode );
    }

    /**
     * Check if the current stream is in blocking mode
     *
     * @return boolean
     */
    public function isBlocking()
    {
        return $this->blocking_mode ? true : false;
    }
    
    /**
     * Check that the stream is valid and it's not at it's end
     *
     * @return true
     */
    public function isOpen()
    {
        return is_resource( $this->stream ) && !feof( $this->stream );
    }

    /**
     * Close this stream
     *
     * @return void
     */
    public function close() 
    {
        if( $this->isOpen() ) {
            fclose( $this->stream );
        }
        $this->stream = null;
    }

    /**
     * Read an amount of data from the stream
     *
     * @param int $size
     * @return string
     */
    public function read( $size )
    {
        if( !$this->isOpen() ) {
            throw new MioClosedException( "Cannot read from a closed stream" );
        }
        return fread( $this->stream, $size );
    }
    
    /**
     * Write data to the stream
     *
     * @param string $data
     * @return boolean true if the whole buffer is written
     */
    public function write( $data=null )
    {
        if( !$this->isOpen() ) {
            throw new MioClosedException( "Cannot write to a closed stream" );
        }
        if( $this->buffer ) {
            $data = $this->buffer . $data;
        }
        $written = @fwrite( $this->stream, $data );
        $this->buffer = substr( $data, $written );
        if( ($length = strlen( $this->buffer )) > $this->buffer_limit ) {
            throw new MioException( "Exceeded stream buffer limit of " . $this->buffer_limit . " by " . ($length - $this->buffer_limit) );
        }
        return $length == 0;
    }

    /**
     * Accept a new connection on a server socket stream
     *
     * @return MioStream
     */
    public function accept( $timeout=0 )
    {
        $stream = @stream_socket_accept( $this->stream, $timeout );
        if( $stream ) {
            return new MioStream( $stream, $this->__toString() . '>' . $stream );
        }
    }

    /**
     * Get the underlying stream resource for use
     * within the select function
     *
     * @return resource
     */
    public function getStream()
    {
        return $this->stream;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __destruct()
    {
        $this->close();
    }
}
