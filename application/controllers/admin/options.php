<?php class Options extends CI_Controller {

	function __construct() {
		parent :: __construct();
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

		$all_options = $this->core_model->optionsGet ( false );
		$this->template ['all_options'] = $all_options;

		// $this->load->vars ( $this->template );

		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';

		$primarycontent =$this->load->view ( 'admin/options/index', true, true );
		$nav =$this->load->view ( 'admin/options/subnav', true, true );
		$primarycontent = $nav . $primarycontent;
		//	$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}


function add() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		if ($_POST) {
			$this->core_model->cacheDelete ( 'cache_group', 'options' );
			$this->core_model->optionsSave ( $_POST );

			redirect ( 'admin/options/index' );
		}

		$all_options = $this->core_model->optionsGet ( false );
		$this->template ['all_options'] = $all_options;

		// $this->load->vars ( $this->template );

		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';

		$primarycontent =$this->load->view ( 'admin/options/add', true, true );
		$nav =$this->load->view ( 'admin/options/subnav', true, true );
		$primarycontent = $nav . $primarycontent;
		//	$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}

	function ajax_delete_by_id() {
		$id = $_POST ['id'];
		if (intval ( $id ) == 0) {

			exit ( 0 );
		} else {

			$this->core_model->optionsDeleteById ( $id );
			exit ( 1 );
		}
	}

}
