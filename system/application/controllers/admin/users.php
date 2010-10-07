<?php

class Users extends Controller {

	function __construct() {

		parent::Controller ();

		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');

	}

	function delete() {

		$id = $this->core_model->getParamFromURL ( 'id' );
		$this->users_model->userDeleteById ( $id );
		redirect ( 'admin/users/index' );

	}

	function users_do_search() {
		if ($_POST ['search_by_keyword']) {
			$togo_tags = "/keyword:{$_POST ['search_by_keyword']}";
			$gogo = site_url ( 'admin/users/index' ) . $togo_tags;
		$gogo = reduce_double_slashes ( $gogo );
		header ( "Location: $gogo " );
		exit ();
		} else {
			$togo_tags = false;
			$tags = $this->core_model->getParamFromURL ( 'keyword' );
			$togo_tags = "/keyword:{$tags}";
			$gogo = site_url ( 'admin/users/index' ) . $togo_tags;
		$gogo = reduce_double_slashes ( $gogo );
		header ( "Location: $gogo " );
		exit ();
		}

	}

	function index() {

		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		if ($this->session->userdata ( 'user' ) == false) {

		//redirect ( 'index' );


		}

		if (array_key_exists ( 'savedata', $_POST )) {

			$this->users_model->saveUser ( $_POST );

		}
		$tags = $this->core_model->getParamFromURL ( 'keyword' );

		$criteria = array ();
		$this->template ['search_by_keyword'] = '';
		if ($tags) {
			$criteria ['search_by_keyword'] = $tags;
			$this->template ['search_by_keyword'] = $tags;
		}

		$results_count = $this->users_model->getUsers ( $criteria, false, true );

		$items_per_page = $this->content_model->optionsGetByKey ( 'admin_default_items_per_page' );

		$content_pages_count = ceil ( $results_count / $items_per_page );

		$curent_page = $this->core_model->getParamFromURL ( 'curent_page' );
		if (intval ( $curent_page ) < 1 || intval ( $curent_page ) > $content_pages_count) {
			$curent_page = 1;
		}

		$page_start = ($curent_page - 1) * $items_per_page;
		$page_end = ($page_start) + $items_per_page;

		$users = $this->users_model->getUsers ( $criteria, array ($page_start, $page_end ), false );

		$this->template ['content_pages_count'] = $content_pages_count;
		//var_dump($content_pages_count);
		$this->template ['content_pages_curent_page'] = $curent_page;

		//get paging urls
		$content_pages = $this->content_model->pagingPrepareUrls ( false, $content_pages_count );
		$this->template ['content_pages_links'] = $content_pages;

		$this->template ['users'] = $users;

		$this->load->vars ( $this->template );

		$layout = $this->load->view ( 'admin/layout', true, true );

		$primarycontent = '';

		$secondarycontent = '';

		$primarycontent = $this->load->view ( 'admin/users/index', true, true );

		$secondarycontent = $this->load->view ( 'admin/users/sidebar', true, true );

		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );

		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );

		//$this->load->view('welcome_message');


		$this->output->set_output ( $layout );

	}

	function edit() {

		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		//if ($this->session->userdata ( 'user' ) == false) {

		//redirect ( 'index' );


		//}

		if ($_POST) {

			$this->users_model->saveUser ( $_POST );
			$gogo = site_url ( 'admin/users/index' ) ;
			$gogo = reduce_double_slashes ( $gogo );
			header ( "Location: $gogo " );
			exit ();

		}
		$tags = $this->core_model->getParamFromURL ( 'id' );
		if (intval ( $tags ) != 0) {
			$criteria = array ();
			//$this->template ['search_by_keyword'] = '';
			if ($tags) {
				$criteria ['id'] = $tags;
				//$this->template ['search_by_keyword'] = $tags;
			}

			$users = $this->users_model->getUsers ( $criteria, false, false );
			$users = $users [0];
		} else {
			$userz = $this->core_model->dbGetTableFields ( TABLE_PREFIX . 'users' );
			foreach ( $userz as $item ) {
				$users [$item] = '';
			}
		}
		//p($users);


		$this->template ['form_values'] = $users;

		$this->load->vars ( $this->template );

		$layout = $this->load->view ( 'admin/layout', true, true );

		$primarycontent = '';

		$secondarycontent = '';

		$primarycontent = $this->load->view ( 'admin/users/edit', true, true );

		$secondarycontent = '';

		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );

		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );

		//$this->load->view('welcome_message');


		$this->output->set_output ( $layout );

	}

}

?>