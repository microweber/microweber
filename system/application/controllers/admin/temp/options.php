<?php class Options extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		if ($_POST) {
			CI::model('core')->cacheDelete ( 'cache_group', 'options' );
			CI::model('core')->optionsSave ( $_POST );
			
			redirect ( 'admin/options/index' );
		}
		
		
		
		
		$all_options = CI::model('core')->optionsGet(false);
		$this->template ['all_options'] = $all_options;
		
		
		
		
		$this->load->vars ( $this->template );
		
		$layout = CI::view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent = CI::view ( 'admin/options/index', true, true );
		//	$secondarycontent = CI::view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}

}
