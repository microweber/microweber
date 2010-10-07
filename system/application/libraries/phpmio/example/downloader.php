<?php
require 'MIO/Selector.php';
require 'MIO/SelectionKey.php';
require 'MIO/Stream.php';
require 'MIO/StreamFactory.php';
require 'MIO/Exception.php';

$urls = array(
    'http://uk.php.net/get/php-5.2.1.tar.bz2/from/this/mirror'  => 'php.tar.bz2',
    'http://dev.mysql.com/get/Downloads/MySQL-5.1/mysql-5.1.15-beta-linux-i686-glibc23.tar.gz/from/http://mirrors.dedipower.com/www.mysql.com/' => 'mysql.tar.gz',
    'http://www.mirrorservice.org/sites/ftp.apache.org/httpd/httpd-2.2.4.tar.bz2' => 'apache.tar.bz2'
);

$selector = new MioSelector();
$streams  = new MioStreamFactory();

foreach( $urls as $url => $file ) {
    // I suppose I should really have a buffer in between them
    // but I think I can be pretty sure the network is going to
    // be slower than the filesystem.
    $selector->register(
        $streams->createFileStream( $url, 'r' ),
        MioSelectionKey::OP_READ,
        $streams->createFileStream( $file, 'w+' )
    );
}

echo "\n";
for( $i=1; ; $i++ ) {
    while( !$count = $selector->select( 100000 ) ) {
        if( $count === false ) {
            $selector->close();
            break 2;
        }
    }

    foreach( $selector->selected_keys as $key ) {
        if( $key->isReadable() ) {
            $key->attachment->write(
                $key->stream->read( 16384 )
            );
        } else {
            echo "Que!\n";
        }
    }
    echo "\r";
    foreach( $urls as $file ) {
        echo $file . ' (' . filesize( $file ) . ') ';
    }
}
echo "\n";
