<?php
/**
 * A factory to consolidate stream creation.
 *
 * @author Rob Young <bubblenut@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class MioStreamFactory 
{
    public function createSocketStream( $host, $port )
    {
        return new MioStream( @fsockopen( $host, $port, $errno=null, $errstr=null ), $host.':'.$port );
    }

    public function createFileStream( $url, $mode )
    {
        return new MioStream( fopen( $url, $mode ), $url );
    }

    public function createServerStream( $socket, $flags=null, $context=null )
    {
        if( !$flags ) {
            $flags = STREAM_SERVER_BIND | STREAM_SERVER_LISTEN;
        }
        if( $context ) {
            $stream = @stream_socket_server( $socket, $errno=null, $errstr=null, $flags, $context );
        } else {
            $stream = @stream_socket_server( $socket, $errno=null, $errstr=null, $flags );
        }
        return new MioStream( $stream, 'server:'.$socket );
    }
}
