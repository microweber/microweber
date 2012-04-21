<?php

class Comments extends CI_Controller {
	
	function __construct() {
		parent :: __construct();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$data = array ( );
		$data ['is_moderated'] = 'n';
		$form_values = CI::model('comments')->commentsGet ( $data );
		$this->template ['new_comments'] = $form_values;
		$this->load->vars ( $this->template );
		
		$data = array ( );
		$data ['is_moderated'] = 'y';
		$limit [0] = 0;
		$limit [1] = 100;
		
		$form_values = CI::model('comments')->commentsGet ( $data );
		$this->template ['old_comments'] = $form_values;
		$this->load->vars ( $this->template );
		
		$this->load->vars ( $this->template );
		
		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent =$this->load->view ( 'admin/comments/index', true, true );
		//$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}
	
	function approve() {
		$id = $_POST ['id'];
		if (intval ( $id ) == 0) {
			exit ( 'id' );
		} else {
			CI::model('comments')->commentApprove ( $id );
		}
	}
	
	function delete() {
	$id = $_POST ['id'];
		if (intval ( $id ) == 0) {
			exit ( 'id' );
		} else {
			CI::model('comments')->commentsDeleteById ( $id );
		}
	}

}

