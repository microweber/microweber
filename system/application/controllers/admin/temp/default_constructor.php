<?php

$the_user = CI::library('session')->userdata ( 'the_user' );

if (empty ( $the_user )) {
	$go = site_url ( 'login' );
	header ( "Location: $go" );
} else {
	
	if ($the_user ['is_admin'] != 'y') {
		$go = site_url ();
		header ( "Location: $go" );
	} else {
	
	}
}

?>