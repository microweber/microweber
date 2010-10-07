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
			$data = $this->users_model->getUsers ( $data );
			$data = $data [0];
			if (empty ( $data )) {
				$data = array ();
				$data ['email'] = $email;
				$data ['password'] = $pass;
				$data ['is_active'] = 'y';
				$data = $this->users_model->getUsers ( $data );
				$data = $data [0];
			}

			if (empty ( $data )) {
				$this->session->unset_userdata ( 'the_user' );
				redirect ( 'login' );
				exit ();
			} else {

				$this->session->set_userdata ( 'the_user', $data );
				$user_session = array ();
				$user_session ['is_logged'] = 'yes';
				$user_session ['user_id'] = $data ['id'];
				$this->session->set_userdata ( 'user_session', $user_session );

				if ($data ["is_admin"] == 'y') {
					redirect ( 'admin/content/posts_manage' );
				} else {
					$go = site_url ();
					header ( "Location: $go" );

				}
				//$data = $data[0];
				//var_dump($data);
				//var_dump($_POST);
				exit ();

			}

		}

		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->load->vars ( $this->template );
		//	$layout = $this->load->view ( 'layout', true, true );
		$primarycontent = $this->load->view ( 'login', true, true );
		print $primarycontent;
		// $layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		//$this->output->set_output ( $primarycontent );


		exit ();
	}

	function leave() {

		$this->session->sess_destroy ();
		$go = site_url ();
		header ( "Location: $go" );

	}
	
function whoami() {

	$the_user = $this->session->userdata ( 'the_user' );
	//..var_dump($the_user);
	 
	
	$the_user['password'] = 'this is hidden';
	//var_dump($the_user);
	
	$the_user  = serialize($the_user);
	exit($the_user);

	}

}

?>