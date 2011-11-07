<?php

class Index extends Controller {

	function __construct() {

		parent::Controller ();

		require_once (APPPATH . 'controllers/default_constructor.php');

		if (defined ( 'INTERNAL_API_CALL' ) == true) {
			$microweber_api = $this;
			$CI = get_instance ();
			return $CI;

		}

	}

	function index() {
 
		require (APPPATH . 'controllers/advanced/index/_controller.php');
		
	}

	function userbase() {

		require (APPPATH . 'controllers/advanced/userbase/_controller.php');

	}
	
	function captcha() {
 
		require (APPPATH . 'controllers/captcha.php');

	}
	
	

	function users() {
		require (APPPATH . 'controllers/advanced/users/_controller.php');

	}

	function dashboard() {
		require (APPPATH . 'controllers/advanced/dashboard/_controller.php');

	}

	function login() {
		$back_to = CI::model('core')->getParamFromURL ( 'back_to' );

		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];

		$username = $this->input->post ( 'username', TRUE );

		$password = $this->input->post ( 'password', TRUE );

		$user_action = $this->input->post ( 'user_action', TRUE );

		if ($user_action == 'register') {

			$q = "select username from " . $table . " where username='$username' ";

			$query = CI::db()->query ( $q );

			$query = $query->row_array ();

			$query = (array_values ( $query ));

			$username = $query [0];

			if ($username != '') {

				$this->template ['message'] = 'This user exists, try another one!';

			} else {

				$data = array ('updated_on' => date ( "Y-m-d h:i:s" ), 'is_active' => 0, 'username' => $username, 'password' => $password );

				$this->db->insert ( $table, $data );

				$this->template ['message'] = 'Success, try login now!';

			}

		} else {

			$q = "select * from " . $table . " where username='$username' and password='$password' and is_active=1";

			$query = CI::db()->query ( $q );

			$query = $query->row_array ();

			if (empty ( $query )) {

				$this->template ['message'] = 'Wrong username or password, or user is disabled!';

			} else {

				CI::library('session')->set_userdata ( 'user', $query );
				if ($back_to == false) {
					redirect ( 'dashboard' );
				} else {
					redirect ( base64_decode ( $back_to ) );
				}

			}

		}

		$this->load->vars ( $this->template );

		$layout = CI::view ( 'layout', true, true );

		$primarycontent = '';

		$secondarycontent = '';

		$primarycontent = CI::view ( 'login', true, true );

		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );

		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );

	//CI::view('welcome_message');


	//CI::library('output')->set_output ( $layout );
	}

}




/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */