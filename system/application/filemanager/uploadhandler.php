<?php
// $Id: extplorer.php 164 2010-05-03 15:06:51Z soeren $
// eXtplorer Flash Upload Handler for Joomla!
if( @$_POST['option'] == 'com_extplorer' && !empty($_POST[session_name()]) && !defined('_JEXEC') ) {
	session_name( substr( $_POST['session_name'], 0 , 32 ) );
	$sess_id = substr( $_POST[session_name()], 0 , 32 );
	$_COOKIE[session_name()] = $sess_id;
	session_id( $sess_id );

	/**DEBUG
	$res = "\r\nTime: ".time()."\r\nSession Name: ".session_name().', Session ID: '.session_id();
	$res .= "\r\nUser Agent String: ".$_SERVER['HTTP_USER_AGENT'];
	$res .= "\r\nCOOKIE: ".print_r( $_COOKIE, true )."\r\nPOST: ".print_r( $_POST, true );
	//die( $res );
	//file_put_contents( 'debug.txt', $res, FILE_APPEND );
	//**/
	
	if( file_exists('../../../configuration.php') ){
		// we need to spoof J! 1.6 and modify the user agent to get the allowance to reuse the existing browser session
		$_SERVER['HTTP_USER_AGENT'] = stripslashes( $_POST['user_agent'] );
		session_start();
		//  we had our fun, enough values set-
		// now just continue with the default Joomla! /administrator/index.php
		require( '../../index.php' ); 
	} else {
		require( dirname(__FILE__).'/index.php' ); 
	}
	
}
