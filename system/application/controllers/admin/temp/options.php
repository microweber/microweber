<?php class Options extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		if ($_POST) {
			$this->core_model->cacheDelete ( 'cache_group', 'options' );
			$this->core_model->optionsSave ( $_POST );
			
			redirect ( 'admin/options/index' );
		}
		
		
		
		
		$all_options = $this->core_model->optionsGet(false);
		$this->template ['all_options'] = $all_options;
		
		
		
		
		$this->load->vars ( $this->template );
		
		$layout = $this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent = $this->load->view ( 'admin/options/index', true, true );
		//	$secondarycontent = $this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//$this->load->view('welcome_message');
		$this->output->set_output ( $layout );
	}

}
