<?php
$img = imagecreate( $_GET['width'], $_GET['height'] );
$bg = imagecolorallocate( $img, 225, 226, 227 );
header( "Content-type: image/png" );
imagepng( $img );
imagecolordeallocate( $bg );
imagedestroy( $img );
?>
