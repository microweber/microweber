<?php
/**
 * A selector manages a list of selection keys that have been
 * registered with it. So long as there is at least one
 * selection key registered with the selector the select method
 * can be called. This simply calls the language function 
 * stream_select and then checks the results against the 
 * registed selection keys and their respective interests.
 *
 * @author Rob Young <bubblenut@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class MioSelector 
{
    private
        $open = true,
        $selection_keys = array(),
        $selected_keys  = array(),
        $key_lookup     = array();

    /**
     * Magic accessor
     */
    public function __get( $key )
    {
        if( 'selection_keys' == $key || 'selected_keys' == $key ) {
            return $this->$key;
        } else {
            throw new InvalidArgumentException("Invalid property access");
        }
    }
    
    /**
     * Register an MioStream with a particular interest set.
     *
     * @param Stream $stream
     * @param int $ops
     * @param object $attached
     *
     * @throws MioException
     * @throws InvalidArgumentException
     */
    public function register( $stream, $ops, $attached=null ) 
    {
        if( !$this->isOpen() ) {
            throw new MioClosedException( "Selector closed, streams cannot be added" );
        }
        if( !$this->hasStream( $stream ) ) {
            $key = new MioSelectionKey( $stream, $ops, $attached );
            $this->selection_keys[] = $key;
            $this->key_lookup[$stream->getStream()] = $key;
        }
    }

    /**
     * Check that this selector is still open
     *
     * @return boolean
     */
    public function isOpen()
    {
        return $this->open;
    }
    
    /**
     * Close this selector and detatch all associated sockets
     *
     * @return void
     */
    public function close()
    {
        $this->open = false;
        foreach( $this->selection_keys as $key ) {
            $key->stream->close();
        }
        $this->selection_keys = array();
        $this->key_lookup     = array();
    }

    /**
     * Check if this selector has a certain stream
     *
     * @param MioStream $stream
     * @return boolean
     */
    public function hasStream( $stream ) 
    {
        return $this->keyFor( $stream ) ? true : false;
    }
    
    /**
     * Get the key associating this selector with a given stream
     *
     * @param MioStream $stream
     * @return MioSelectionKey
     */
    public function keyFor( $stream )
    {
        $s = $stream->getStream();
        if( isset( $this->key_lookup[ $s ] ) ) {
            return $this->key_lookup[ $s ];
        }
    }

    /**
     * Close and remove a key
     *
     * @param MioSelectionKey
     */
    public function removeKey( $key )
    {
        $new_keys = array();
				
				unset($this->key_lookup[$key->stream->getStream()]);
				$key->stream->close();
        foreach( $this->selection_keys as $k ) {
            if( $k !== $key ) {
                $new_keys[] = $k;
            }
        }
        $this->selection_keys = $new_keys;
    }

    /**
     * Run the select, add changed streams to the selected_keys
     * array and return the count of changed keys
     *
     * @param int $timeout
     * @return int The number of streams selected
     */
    public function select( $timeout=100000 )
    {
        if(!$this->selection_keys) {
            return false;
        }

        $this->selected_keys = array();
        $read = $write = $except = $closed = array();

        foreach( $this->selection_keys as $key ) {
            if( !$key->stream->isOpen() ) {
                $closed[]  = $key;
            } else {
                $key->resetReadyOps();
                if( $key->interestedIn( MioSelectionKey::OP_READ | MioSelectionKey::OP_ACCEPT ) ) {
                    $read[]  = $key->stream->getStream();
                } elseif( $key->interestedIn( MioSelectionKey::OP_WRITE ) ) {
                    $write[] = $key->stream->getStream();
                }
            }
        }

        foreach( $closed as $key ) {
            $this->removeKey( $key );
        }
        
        if( !$read && !$write ) {
            return false;
        }
        $count  = 0;
        if( ($result = stream_select( $read, $write, $except, 0, $timeout )) > 0 ) {
            $this->_selectKeys( $read, MioSelectionKey::OP_READ | MioSelectionKey::OP_ACCEPT );
            $this->_selectKeys( $write, MioSelectionKey::OP_WRITE );
            $count = count( $read ) + count( $write );
        }
        return $count;
    }
    
    /**
     * Process an array of keys for a particular operation type
     *
     * @param array $lookup  Reverse lookup to find the selection key
     * @param array $streams Array of streams
     * @param int   $ops
     */
    private function _selectKeys( $streams, $ops )
    {
        foreach( $streams as $stream ) {
            $key = $this->key_lookup[$stream];
            $key->addReadyOp( $ops );
            $this->selected_keys[] = $key;
        }
    }

    public function __toString()
    {
        return 'Selector (' . count( $this->selection_keys ) . ':' . count( $this->selected_keys ) . ")";
    }

    public function __destruct()
    {
        $this->close();
    }
}

