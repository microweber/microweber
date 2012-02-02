<?php

class Core extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		if (CI::library('session')->userdata ( 'user' ) == false) {
			//redirect ( 'index' );
		}
		
		$this->load->vars ( $this->template );
		
		$layout = CI::view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent = CI::view ( 'admin/index', true, true );
		//$layout = str_ireplace ( '{primarycontent }', $primarycontent, $layout );
		//$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}
	
	function reorderMedia() {
		$positions = $_POST ['positions'];
		if (! empty ( $positions )) {
			CI::model('core')->mediaReOrder ( $positions );
		}
		exit ();
	}
	
	function mediaDelete() {
		$id = $_POST ['id'];
		if (intval ( $id ) != 0) {
			CI::model('core')->mediaDelete ( $id );
		}
		exit ();
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */