<?php



class Users extends Controller {

	

	function __construct() {

		parent::Controller ();

		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');

	

	}

	function delete() {


		$id = CI::model('core')->getParamFromURL ( 'id' );
		CI::model('users')->userDeleteById ( $id );
		redirect ( 'admin/users/index' );


}	
	
	
	

	function index() {

		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		

		if (CI::library('session')->userdata ( 'user' ) == false) {

			//redirect ( 'index' );

		}

		

		if ($_POST) {

			CI::model('users')->saveUser ( $_POST );

		}

		

		$users = CI::model('users')->getUsers ();

		

		$this->template ['users'] = $users;

		

		$this->load->vars ( $this->template );

		

		$layout = CI::view ( 'admin/layout', true, true );

		$primarycontent = '';

		$secondarycontent = '';

		

		$primarycontent = CI::view ( 'admin/users/index', true, true );

		$secondarycontent = CI::view ( 'admin/users/sidebar', true, true );

		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );

		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );

		//CI::view('welcome_message');

		CI::library('output')->set_output ( $layout );

	}



}

?>