<?php
if ($this->users_model->is_logged_in () == false) {
	if (($_POST ['username']) and ($_POST ['password'])) {
		$try_to_login = $this->users_model->logIn ();
		print json_encode ( $try_to_login );
		exit ();
	} else {
		exit ( 'login required' );
	}
} else {
	$loggeduser['ok'] ='ok';
	//$loggeduser ['password'] = false;
//	print json_encode ( $loggeduser );
//	exit ();
}

?>