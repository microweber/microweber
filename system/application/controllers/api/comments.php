<?php

class Comments extends Controller {

	function __construct() {

		parent::Controller ();

		require_once (APPPATH . 'controllers/default_constructor.php');
		//p($user_session);
		require_once (APPPATH . 'controllers/api/default_constructor.php');

		if ($this->users_model->is_logged_in () == false) {
			//    exit ( 'Login required' );
		}

	}

	function index() {
		exit ( 'Comments api is here!' );
	}
	function comments_post() {
		if (! $_POST) {
			exit ( 'No $_POST' );
		}

		if ($_POST) {

			$_POST ['to_table_id'] = $this->core_model->securityDecryptString ( $_POST ['to_table_id'] );
			$_POST ['to_table'] = $this->core_model->securityDEcryptString ( $_POST ['to_table'] );

			if (intval ( $_POST ['to_table_id'] ) == 0) {
				exit ( 'to_table_id' );
			}

			if (($_POST ['to_table']) == '') {
				exit ( 'to_table' );
			}

			$save = $this->comments_model->commentsSave ( $_POST );

			print $save;

		//var_dump ( $_POST );


		}

		//$this->core_model->cacheDeleteAll ();
		exit ();
	}

	function comments_list() {
		if (! ($_POST)) {
			exit ( "ERROR: you must send $_POST ['to_table'] and $_POST ['to_table_id'] by post" );
		}

		$comments = array ();
		$comments ['to_table'] = addslashes ( $_POST ['to_table'] );
		$comments ['to_table_id'] = addslashes ( $_POST ['to_table_id'] );
		$comments = $this->comments_model->commentsGet ( $comments );

		$this->template ['comments'] = $comments;

		$this->load->vars ( $this->template );
		$layout = $this->load->file ( APPPATH . 'controllers/api/views/' . __FUNCTION__ . '.php', true );



		$layout = $this->content_model->applyGlobalTemplateReplaceables ( $layout );

		$this->output->set_output ( $layout );
		//var_dump ($content_filename_pre, $files );


	}

}



