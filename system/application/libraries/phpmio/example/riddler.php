<?php
require 'MIO/Selector.php';
require 'MIO/SelectionKey.php';
require 'MIO/Stream.php';
require 'MIO/StreamFactory.php';
require 'MIO/Exception.php';

/**
 * Message generator class for spewing out different data
 *
 */
class MessageGen
{
    private $count=0;
    public function getMessage()
    {
        switch( $this->count ) {
        case 0:
            $message = "My first is in tea but not in leaf";
            break;
        case 1:
            $message = "My second is in teapot and also in teeth";
            break;
        case 2:
            $message = "My third is in caddy but not in cosy";
            break;
        case 3:
            $message = "My fourth is in cup but not in rosy";
            break;
        case 4:
            $message = "My fifth is in herbal and also in health";
            break;
        case 5:
            $message = "My last is in drink, so what can I be";
            break;
        case 6:
            $message = "I'm there in a classroom, do you listen to me?";
            break;
        default:
            return false;
        }
        return $message;
    }
    public function inc()
    {
        $this->count++;
    }
}

/**
 * Echo class for responding to data
 */
class EchoGen
{
    private $message = '';
    public function put( $message )
    {
        $this->message = $message;
    }
    public function get()
    {
        return "The message is: " . $this->message;
    }
}

$selector = new MioSelector();
$streams  = new MioStreamFactory();

echo "Creating server...";
$selector->register(
    $streams->createServerStream( '127.0.0.1:8888' ),
    MioSelectionKey::OP_ACCEPT
);
echo "DONE\n";

$i=0;
while( true ) {
    /**
     * Add socket streams five at a time otherwise the server
     * buffer gets filled up and fsockopen blocks
     */
    for( $j=0; $j<5 && $i<100; $i++, $j++ ) {
        echo "Creating client " . ($i+1) . "...";
        $selector->register(
            $streams->createSocketStream( '127.0.0.1', 8888 ),
            MioSelectionKey::OP_WRITE,
            new MessageGen()
        );
        echo "DONE\n";
    }

    while( !$count = $selector->select() ) {
        if( $count === false || ($count===0 && count( $selector->selection_keys )==1)) {
            $selector->close();
            break 2;
        }
    }

    foreach( $selector->selected_keys as $key ) {
        if( $key->isAcceptable() ) {
            while( $stream = $key->stream->accept() ) {
                $selector->register(
                    $stream,
                    MioSelectionKey::OP_READ,
                    new EchoGen()
                );
            }
        } elseif( $key->isReadable() ) {
            $data = $key->stream->read( 4096 );
            if( $key->attachment instanceof EchoGen ) {
                $key->attachment->put( $data );
            } elseif( $key->attachment instanceof MessageGen ) {
                if( !preg_match( '/(The message.*)/', $data, $matches )) {
                    echo $key->stream . " BAD MATCH\n";
                    echo $data . "\n";
                } else {
                    echo $key->stream . ' ' . $matches[1] . "\n";
                }
            } else {
                throw new Exception( "Invalid attachment type" );
            }
            $key->setInterestOps( MioSelectionKey::OP_WRITE );
        } elseif( $key->isWritable() ) {
            if( $key->attachment instanceof EchoGen ) {
                $data = $key->attachment->get();
            } elseif( $key->attachment instanceof MessageGen ) {
                if( $data = $key->attachment->getMessage() ) {
                    $key->attachment->inc();
                    echo $key->stream . " Sending: " . $data . "\n";
                } else {
                    echo $key->stream . " Remove Stream (" . count( $selector->selection_keys ) . ")\n";
                    $selector->removeKey( $key );
                    continue;
                }
            }
            $key->stream->write( $data );
            $key->setInterestOps( MioSelectionKey::OP_READ );
        }
    }
    flush();
}
