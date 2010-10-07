<?php

class Reports extends Controller {

	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');

	}

	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$data = array ();
		$reports_for = $this->core_model->getParamFromURL ( 't' );
		if ($reports_for != false) {
			// $data['to_table'] = $reports_for;
			$data [] = array ('to_table', $reports_for );
		}

		$reports = $this->content_model->reportsGet ( $data, false );

		$this->template ['reports'] = $reports;

		$this->load->vars ( $this->template );

		$layout = $this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';

		$primarycontent = $this->load->view ( 'admin/reports/index', true, true );
		//$secondarycontent = $this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//$this->load->view('welcome_message');
		$this->output->set_output ( $layout );
	}

	function approve() {
		$id = $_POST ['to_table_id'];
		if (intval ( $id ) == 0) {
			exit ( 'id' );
		} else {

			global $cms_db_tables;
			$table = $cms_db_tables ['table_reports'];
			$q = "delete from {$table} where to_table='{$_POST['to_table']}' and to_table_id='{$_POST['to_table_id']}'  ";
			$q = $this->core_model->dbQ ( $q );
			$this->core_model->cleanCacheGroup( 'reports');

		}
	}

	function delete() {
		$id = $_POST ['to_table_id'];
		if (intval ( $id ) == 0) {
			exit ( 'id?' );
		} else {
			global $cms_db_tables;
			$id = intval($_POST['to_table_id']);
			$real_table = $cms_db_tables [$_POST['to_table']];


			$q = "delete from {$real_table} where id={$id}  ";

			$q = $this->core_model->dbQ ( $q );


			$table = $cms_db_tables ['table_reports'];
			$q = "delete from {$table} where to_table='{$_POST['to_table']}' and to_table_id='{$_POST['to_table_id']}'  ";
			$q = $this->core_model->dbQ ( $q );
			$this->core_model->cleanCacheGroup( 'reports');

		 switch ($_POST['to_table']) {
		 	case 'table_content':
		 	$this->core_model->cleanCacheGroup( 'content');
		 	break;

		 	case 'table_comments':
		 	$this->core_model->cleanCacheGroup( 'comments');
		 	break;
		 	default:
		 		$this->core_model->cacheDeleteAll();
		 	break;
		 }

			//$this->content_model->commentsDeleteById ( $id );
		}
	}

}

