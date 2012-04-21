<?php

class Template extends CI_Controller {
	
	function __construct() {
		
		parent :: __construct();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		//p($user_session);
		require_once (APPPATH . 'controllers/api/default_constructor.php');
	
	}
	
	function index() {
		var_dump ( $_COOKIE );
	}
	
	function install_module() {
		$mmd5 = md5 ( $_POST ['module'] );
		$check_if_uninstalled = MODULES_DIR . '_system/' . $mmd5 . '.php';
		@unlink ( $check_if_uninstalled );
	}
	
	function uninstall_module() {
		$mmd5 = md5 ( $_POST ['module'] );
		$check_if_uninstalled = MODULES_DIR . '_system/' . $mmd5 . '.php';
		@touch ( $check_if_uninstalled );
	}
	
	function test1() {
		//	var_dump($_POST);
		//print mt_rand(1, 65654);
		exit ();
	}
	
	function encode_post() {
		$post = serialize ( $_POST );
		$post = base64_encode ( $post );
		
		//print mt_rand(1, 65654);
		exit ( $post );
	}
	
	function layoutGetHTML() {
		$data = $this->template_model->layoutGetHTMLByDirName ( $_REQUEST ['name'] );
		print $data;
	}
	
	function layoutsList() {
		$active = trim ( $_REQUEST ['layout'] );
		$type = trim ( $_REQUEST ['type'] );
		$this->template ['type'] = $type;
		
		$this->template ['active'] = $active;
		$options = array ();
		$options ['type'] = $type;
		$data = $this->template_model->layoutsList ( $options );
		$view = TEMPLATES_DIR . 'layouts/layouts_list.php';
		$this->template ['data'] = $data;
		if (is_readable ( $view ) == true) {
			$this->load->vars ( $this->template );
			$layout = $this->load->file ( $view, true );
			$layout = $this->content_model->applyGlobalTemplateReplaceables ( $layout, $global_template_replaceables = false );
			$this->output->set_output ( $layout );
		} else {
			exit ( "Error: the file {$view} is not readable or does not exist." );
		}
	}
	
	function stylesList() {
		$data = $this->template_model->stylesList ( trim ( $_REQUEST ['layout'] ) );
		$active = trim ( $_REQUEST ['active_style'] );
		$this->template ['active'] = $active;
		$view = TEMPLATES_DIR . 'layouts/styles_list.php';
		$this->template ['data'] = $data;
		if (is_readable ( $view ) == true) {
			$this->load->vars ( $this->template );
			$layout = $this->load->file ( $view, true );
			$layout = $this->content_model->applyGlobalTemplateReplaceables ( $layout, $global_template_replaceables = false );
			$this->output->set_output ( $layout );
		} else {
			exit ( "Error: the file {$view} is not readable or does not exist." );
		}
	}
	
	function styleGetCSSPaths() {
		$layout = (trim ( $_REQUEST ['layout'] ));
		$style = (trim ( $_REQUEST ['style'] ));
		
		if ($layout == '') {
			exit ( "Error: Please define $layout by $ REQUEST param" );
		} else {
			$styles = $this->template_model->styleGetCSSURLsAsString ( $layout, $style );
			exit ( $styles );
		}
	}
	
	function microweberTagsGenerate() {
		$data = (trim ( $_POST ['params'] ));
		$data = @json_decode ( $data, 1 );
		
		if (! is_array ( $data )) {
			//	exit ();
		}
		if ($_POST ['options']) {
			$options = (trim ( $_POST ['options'] ));
			$options = @json_decode ( $options, 1 );
			
			if (! is_array ( $options )) {
				$options = false;
			}
		}
		if (empty ( $data )) {
			exit ();
		}
		//var_dump ( $data );
		if ($data == false) {
			exit ( 'Error: Please send params by POST. $_POST[params] must not be blank ' );
		} else {
			$data = $this->template_model->microweberTagsGenerate ( $data, $options );
			print $data;
			exit ();
		}
	
	}

}



