<?php
$user = user_id ();
if (intval ( $user ) == 0) {
	$redir = site_url ( 'users/user_action:login' );
	header ( "Location: " . $redir );
	echo "<meta http-equiv=\"refresh\" content=\"0;url=" . "{$redir}\" />";
	//ob_end_flush(); //now the headers are sent
	exit ();
 
}

?>