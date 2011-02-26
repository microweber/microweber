<?php


 

/*
$agent = $_SERVER ['HTTP_USER_AGENT'];
$the_user = CI::library('session')->userdata ( 'the_user' );

if ($_POST and $_POST ['username'] and $_POST ['password']) {

	if (empty ( $the_user )) {
		if ($_POST ['username']) {
			if ($_POST ['password']) {
				$user = $_POST ['username'];
				$pass = $_POST ['password'];

				$data = array ();
				$data ['username'] = $user;
				$data ['password'] = $pass;
				$data ['is_active'] = 'y';
				$data = CI::model('users')->getUsers ( $data );
				$data = $data [0];

				if (empty ( $data )) {
					CI::library('session')->unset_userdata ( 'the_user' );

				} else {

					CI::library('session')->set_userdata ( 'the_user', $data );
					$user_session = array ();
					$user_session ['is_logged'] = 'yes';
					$user_session ['user_id'] = $data ['id'];
					CI::library('session')->set_userdata ( 'user_session', $user_session );
					$the_user = CI::library('session')->userdata ( 'the_user' );
				}

			}

		}
	}

} else {

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
}*/