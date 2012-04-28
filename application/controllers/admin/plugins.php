<?php

class Plugins extends CI_Controller {
	
	function __construct() {
		parent :: __construct();
		require_once (APPPATH . 'controllers/default_constructor.php');
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		// $this->load->vars ( $this->template );
		
		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		//$primarycontent =$this->load->view ( 'admin/comments/index', true, true );
		//$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{content}', $primarycontent, $layout );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}
	
	function load() {
		$plugin_name = $this->uri->segment ( 4 );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->template ['pluginName'] = $plugin_name;
		
		// $this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		$plugindata = $this->core_model->plugins_getPluginConfig ( $plugin_name );
		//
				 
		
		// print  PLUGINS_DIRNAME . $dirname .$plugin_name.'/'.$plugindata['plugin_admin_dir']. '/controller.php' ;
		if (is_file ( PLUGINS_DIRNAME . $dirname . $plugin_name . '/' . $plugindata ['plugin_admin_dir'] . '/controller.php' ) == true) {
			$firecms = get_instance ();
			define ( 'THIS_PLUGIN_URL',  site_url('admin/plugins/load'). '/' . $plugin_name . '/' );
			define ( 'THIS_PLUGIN_DIRNAME', PLUGINS_DIRNAME . $dirname . $plugin_name . '/' );
			define ( 'THIS_PLUGIN_DIRNAME_ADMIN', PLUGINS_DIRNAME . $dirname . $plugin_name . '/' . $plugindata ['plugin_admin_dir'] . '/' );
								require_once (PLUGINS_DIRNAME . $dirname . $plugin_name . '/' . $plugin_name . '_model.php');
			
			require_once (PLUGINS_DIRNAME . $dirname . $plugin_name . '/' . $plugindata ['plugin_admin_dir'] . '/controller.php');
			
		}
		
		//$primarycontent =$this->load->view ( 'admin/comments/index', true, true );
		//$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
				$layout = str_ireplace ( '{content}', $primarycontent, $layout );
		
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		CI::library('output')->set_output ( $layout );
	}

}

