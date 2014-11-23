<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

// ------------------------------------------------------------------------
//	HybridAuth End Point
// ------------------------------------------------------------------------

require_once( "Hybrid/Auth.php" );
require_once( "Hybrid/Endpoint.php" ); 
try {
Hybrid_Endpoint::process();
} catch( Exception $e ) {
die("<b>got an error!</b> " . $e -> getMessage());
}
//Hybrid_Endpoint::process();
