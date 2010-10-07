<?php

class js extends Controller {

	function __construct() {

		parent::Controller ();

		require_once (APPPATH . 'controllers/default_constructor.php');
		//p($user_session);
	//	require_once (APPPATH . 'controllers/api/default_constructor.php');


	}

	function index() {
		header ( 'Content-type: application/javascript' );
		$files = readDirIntoArray ( APPPATH . 'controllers/api/js/', 'files' );

		$layout = $layout . "\n\n\n // File: _php.default.min.js \n\n" . $this->load->file ( APPPATH . 'controllers/api/js/' . '_php.default.min.js', true );


		$layout = $layout . "\n\n\n // File: _mw.js \n\n" . $this->load->file ( APPPATH . 'controllers/api/js/' . '_mw.js', true );
$layout = $layout . "\n\n\n // File: utils.js \n\n" . $this->load->file ( APPPATH . 'controllers/api/js/' . 'utils.js', true );



		foreach ( $files as $file ) {
			if (substr ( $file, - 2 ) == 'js') {
				if (($file != '_mw.js') and ($file != 'utils.js')) {



					$this->load->vars ( $this->template );
					$layout = $layout . "\n\n\n // File: $file \n\n" . $this->load->file ( APPPATH . 'controllers/api/js/' . $file, true );
				}


			}

		}
		$layout = $this->content_model->applyGlobalTemplateReplaceables ( $layout );

		$this->output->set_output ( $layout );
		//var_dump ($content_filename_pre, $files );


	}

}







