<?php

class Index extends Controller {
	
	function __construct() {
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	}
	
	function index() {
		$is_admin = is_admin ();
		if ($is_admin == false) {
			$go = site_url ( 'login' );
			safe_redirect ( $go );
		}
		
		$action = url_param ( 'action' );
		
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/layout', true, true );
		
		if ($action == false) {
			$primarycontent = CI::view ( 'admin/index', true, true );
		} else {
			$primarycontent = CI::view ( 'admin/' . $action, true, true );
		
		}
		
		$layout = str_ireplace ( '{content}', $primarycontent, $layout );
		$layout = CI::model ( 'template' )->parseMicrwoberTags ( $layout );
		CI::library ( 'output' )->set_output ( $layout );
	}
	
	
		function mercury() {
		$is_admin = is_admin ();
		if ($is_admin == false) {
			$go = site_url ( 'login' );
			safe_redirect ( $go );
		}
		
		$layout = CI::view ( 'admin/mercury', true, true );
		
		$layout = CI::model('template')->parseMicrwoberTags ( $layout );
		CI::library ( 'output' )->set_output ( $layout );
	}
	
	function toolbar() {
		$is_admin = is_admin ();
		if ($is_admin == false) {
			$go = site_url ( 'login' );
			safe_redirect ( $go );
		}
		
		$layout = CI::view ( 'admin/toolbar', true, true );
		
		//$layout = CI::model('template')->parseMicrwoberTags ( $layout );
		CI::library ( 'output' )->set_output ( $layout );
	}
	
	function edit() {
		//CI::library ( 'session' )->set_userdata ( 'editmode', false );
		$is_admin = is_admin (); 
		if ($is_admin == false) {
			$go = site_url ( 'login' );
			safe_redirect ( $go );
		}
		
		$layout = CI::view ( 'admin/iframe', true, true );
		$layout = CI::model ( 'template' )->parseMicrwoberTags ( $layout );
		//$layout = CI::model('template')->parseMicrwoberTags ( $layout );
		CI::library ( 'output' )->set_output ( $layout );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */