<?php
if (CI::model ( 'users' )->is_logged_in () == false) {
	if (($_POST ['username']) and ($_POST ['password'])) {
		$try_to_login = CI::model ( 'users' )->logIn ();
		print json_encode ( $try_to_login );
		exit ();
	} else {
		
		$try_to_login = array('fail'=>'fail');
		print json_encode ( $try_to_login );
		exit ();
		//exit ( 'login required' );
	}
} else {
	$loggeduser['ok'] ='ok';
	//$loggeduser ['password'] = false;
	print json_encode ( $loggeduser );
	exit ();
}

?>