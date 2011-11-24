<?php

class Index extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	}
	
	function index() {
		$gogo = site_url ( 'admin/content/posts_manage' ) . $togo_categories . $togo_tags;
		$gogo = reduce_double_slashes ( $gogo );
		header ( "Location: $gogo " );
		//var_dump ( $gogo );
		exit ();
		
		//http://test3.ooyes.net/admin
		
		$this->template ['functionName'] = strtolower(__FUNCTION__);

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
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */