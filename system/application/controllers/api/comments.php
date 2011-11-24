<?php

class Comments extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		//p($user_session);
		require_once (APPPATH . 'controllers/api/default_constructor.php');
		
		if (CI::model('users')->is_logged_in () == false) {
			//    exit ( 'Login required' );
		}
	
	}
	
	function index() {
		exit ( 'Comments api is here!' );
	}
	
	function delete() {
		
		if (! $_POST ['id']) {
			exit ( 'No $_POST' );
		}
		$id1 = intval ( $_POST ['id'] );
		
		$id = CI::model('comments')->commentGetById ( $id1 );
		if ($id ['created_by'] == user_id ()) {
			$id = CI::model('comments')->commentsDeleteById ( $id1 );
			exit ( 'yes' );
		} else {
			exit ( 'no' );
		}
	
	}
	
	function comments_post() {
		if (! $_POST) {
			exit ( 'No $_POST' );
		}
		
		if ($_POST) {
			
			$_POST ['to_table_id'] = CI::model('core')->securityDecryptString ( $_POST ['to_table_id'] );
			$_POST ['to_table'] = CI::model('core')->securityDEcryptString ( $_POST ['to_table'] );
			
			if (intval ( $_POST ['to_table_id'] ) == 0) {
				exit ( 'to_table_id' );
			}
			
			if (($_POST ['to_table']) == '') {
				exit ( 'to_table' );
			}
			
			$save = CI::model('comments')->commentsSave ( $_POST );
			
			print $save;
			
		//var_dump ( $_POST );
		

		}
		
		//CI::model('core')->cacheDeleteAll ();
		exit ();
	}
	
	function comments_list() {
		if (! ($_POST)) {
			exit ( "ERROR: you must send $ POST array. Read the docs" );
		}
		
		//p($_POST);
		$comments = array ();
		
		if ($_POST ['to_table'] == false) {
			$_POST ['to_table'] = $_POST ['for'];
		}
		
		if ($_POST ['to_table_id'] == false) {
			$_POST ['to_table_id'] = $_POST ['id'];
		}
		
		if ($_POST ['to_table_id'] == false) {
			$_POST ['to_table_id'] = $_POST ['content_id'];
		}
		
		$comments ['to_table'] = CI::model('core')->securityDecryptString ( $_POST ['to_table'] );
		$comments ['to_table_id'] = CI::model('core')->securityDecryptString ( $_POST ['to_table_id'] );
		$comments ['display'] = CI::model('core')->securityDecryptString ( $_POST ['display'] );
		$comments ['to_table'] = CI::model('core')->guessDbTable ( $comments ['to_table'] );
		//print_r ( $comments );
		

		if ($comments ['display'] == false) {
			$comments ['display'] = 'default';
		}
		comments_list ( $content_id = $comments ['to_table_id'], $display = $comments ['display'], $for = $comments ['to_table'], $display_params = array () );
		
	/*	$comments = CI::model('comments')->commentsGet ( $comments );

		$this->template ['comments'] = $comments;

		$this->load->vars ( $this->template );
		$layout = $this->load->file ( APPPATH . 'controllers/api/views/' . __FUNCTION__ . '.php', true );



		$layout = CI::model('content')->applyGlobalTemplateReplaceables ( $layout );

		CI::library('output')->set_output ( $layout );*/
	//var_dump ($content_filename_pre, $files );
	

	}

}



