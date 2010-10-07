<?php

class Comments extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$data = array ( );
		$data ['is_moderated'] = 'n';
		$form_values = $this->content_model->commentsGet ( $data );
		$this->template ['new_comments'] = $form_values;
		$this->load->vars ( $this->template );
		
		$data = array ( );
		$data ['is_moderated'] = 'y';
		$limit [0] = 0;
		$limit [1] = 100;
		
		$form_values = $this->content_model->commentsGet ( $data );
		$this->template ['old_comments'] = $form_values;
		$this->load->vars ( $this->template );
		
		$this->load->vars ( $this->template );
		
		$layout = $this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent = $this->load->view ( 'admin/comments/index', true, true );
		//$secondarycontent = $this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//$this->load->view('welcome_message');
		$this->output->set_output ( $layout );
	}
	
	function approve() {
		$id = $_POST ['id'];
		if (intval ( $id ) == 0) {
			exit ( 'id' );
		} else {
			$this->content_model->commentApprove ( $id );
		}
	}
	
	function delete() {
	$id = $_POST ['id'];
		if (intval ( $id ) == 0) {
			exit ( 'id' );
		} else {
			$this->content_model->commentsDeleteById ( $id );
		}
	}

}

