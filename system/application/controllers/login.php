<?php

class Login extends Controller {

	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
	}

	function index() {
 
		if ($_POST) {

			$user = $_POST ['username'];
			$pass = $_POST ['password'];
			$email = $_POST ['email'];

			$data = array ();
			$data ['username'] = $user;
			$data ['password'] = $pass;
			$data ['is_active'] = 'y';
			$data = CI::model('users')->getUsers ( $data );
			$data = $data [0];
			if (empty ( $data )) {
				$data = array ();
				$data ['email'] = $email;
				$data ['password'] = $pass;
				$data ['is_active'] = 'y';
				$data = CI::model('users')->getUsers ( $data );
				$data = $data [0];
			}

			if (empty ( $data )) {
				CI::library('session')->unset_userdata ( 'the_user' );
				safe_redirect ( 'login' );
				exit ();
			} else {

				CI::library('session')->set_userdata ( 'the_user', $data );
				$user_session = array ();
				$user_session ['is_logged'] = 'yes';
				$user_session ['user_id'] = $data ['id'];
				CI::library('session')->set_userdata ( 'user_session', $user_session );

				if ($data ["is_admin"] == 'y') {
					safe_redirect ( 'admin' );
				} else {
					$go = site_url ();
					safe_redirect ( "$go" );

				}
				//$data = $data[0];
				//var_dump($data);
				//var_dump($_POST);
				exit ();

			}

		}

		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->load->vars ( $this->template );
		//	$layout = CI::view ( 'layout', true, true );
		$primarycontent = CI::view ( 'login', true, true );
		print $primarycontent;
		// $layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		//CI::library('output')->set_output ( $primarycontent );


		exit ();
	}

	function leave() {

		CI::library('session')->sess_destroy ();
		$go = site_url ();
		header ( "Location: $go" );

	}
	
function whoami() {

	$the_user = CI::library('session')->userdata ( 'the_user' );
	//..var_dump($the_user);
	 
	
	$the_user['password'] = 'this is hidden';
	//var_dump($the_user);
	
	$the_user  = serialize($the_user);
	exit($the_user);

	}

}
