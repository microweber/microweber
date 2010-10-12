<?php

global $cms_db_tables;
$table = $cms_db_tables ['table_users'];
$this->users_model->notificationsParseFromLog ();
$this->template ['load_tiny_mce'] = true;
$this->template ['no_breadcrumb_navigation'] = true;

$layout = false;

$global_template_replaceables = false;

$content = array ();

$content ['content_layout_file'] = 'default_layout.php';

$user_action = $this->core_model->getParamFromURL ( 'user_action' );

$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {

	define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );

}

$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

$the_template_url = site_url ( 'userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template );

$the_template_url = $the_template_url . '/';

define ( "TEMPLATE_URL", $the_template_url );

$user_session = array ();

$user_session = $this->session->userdata ( 'user_session' );
$the_user = $this->session->userdata ( 'the_user' );
$user = $this->session->userdata ( 'user' );
//	var_dump($user, $the_user, $user_session);
//
$the_userid = false;
if (intval ( $the_user ['id'] ) != 0) {
	$the_userid = intval ( $the_user ['id'] );
}

if ($user_session ['is_logged'] != 'yes') {

	// 	var_dump($user_session);
	if (($user_action != 'login') && ($user_action != 'login_ajax') && ($user_action != 'register') && ($user_action != 'forgotten_pass') && ($user_action != 'activate')) {

		redirect ( 'users/user_action:login' );

	}

} else {
	require (APPPATH . 'controllers/advanced/users/force_profile_complete.php');

}
$this->template ['user_action'] = $user_action;
$this->load->vars ( $this->template );

//var_dump($user_action);
switch ($user_action) {

	case 'profile' :
		require (APPPATH . 'controllers/advanced/users/profile.php');

		break;

	case 'password' :
		require (APPPATH . 'controllers/advanced/users/password.php');

		break;

	case 'register' :
		require (APPPATH . 'controllers/advanced/users/register.php');

		break;

	case 'activate' :

		//				$activationCode = '8758d3a5cdbcbfe773ff3758db541b10';
		$activationCode = $this->core_model->getParamFromURL ( 'code' );

		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];

		$query = "UPDATE {$table} SET is_active = 'y' WHERE MD5(id) = '{$activationCode}'";
		$this->db->query ( $query );

		redirect ( 'users/user_action:login' );

		break;
	case 'login' :

		require (APPPATH . 'controllers/advanced/users/login.php');
		break;

	case 'login_ajax' :

		require (APPPATH . 'controllers/advanced/users/login_ajax.php');
		//	exit ();
		break;

	case 'post_delete' :

		require (APPPATH . 'controllers/advanced/users/post_delete.php');

		break;

	case 'post' :

		require (APPPATH . 'controllers/advanced/users/post.php');
		break;
	case 'posts' :

		require (APPPATH . 'controllers/advanced/users/posts.php');

		break;

	case 'content-groups' :

		require (APPPATH . 'controllers/advanced/users/content_groups.php');

		break;

	case 'sidebar-manager' :

		require (APPPATH . 'controllers/advanced/users/sidebar_manager.php');

		break;

	case 'exit' :

		$this->session->unset_userdata ( 'user_session' );
		$this->session->unset_userdata ( 'user' );
		$this->session->unset_userdata ( 'the_user' );

		redirect ( 'users' );

		break;
	case 'forgotten_pass' :
		require (APPPATH . 'controllers/advanced/users/forgotten_pass.php');
		break;

	case 'comment_delete' :

		$content_id = $this->core_model->getParamFromURL ( 'id' );

		$check_is_permisiions_error = false;

		if (intval ( $content_id ) != 0) {

			$get_id = array ();
			$get_id ['id'] = $content_id;
			$get_id = $this->comments_model->commentsGet ( $get_id );
			$get_id = $get_id [0];

			$post_id = $get_id ['to_table_id'];
			$get_id = array ();

			$get_id ['id'] = $post_id;

			$get_id = $this->content_model->getContent ( $get_id );

			$get_id = $get_id [0];

			//var_dump($get_id);
			if (! empty ( $get_id )) {

				if ($get_id ['created_by'] != $this->core_model->userId()) {

					//var_dump($get_id ['created_by'], $user_session ['user_id']);
					//redirect ( 'users/posts' );
					exit ( 'error created_by' );
				} else {

					//$this->template ['form_values'] = $get_id;


					//var_dump($content_id);
					$this->comments_model->commentsDeleteById ( $content_id );
					exit ( 'ok' );
					//redirect ( 'users/posts' );


				}

			} else {
				exit ( 'error content' );
				//redirect ( 'users/posts' );


			}

		}

		break;

	default :

		$user_content = array ();

		$user_content ['content_type'] = 'post';

		$user_content ['created_by'] = $user_session ['user_id'];

		$user_content = $this->content_model->getContent ( $user_content, $orderby = array ('updated_on', 'DESC' ), $limit = false, $count_only = false );

		//	var_dump ( $user_content );
		$this->template ['user_content'] = $user_content;

		$user_session ['user_action'] = $user_action;

		$this->load->vars ( $this->template );

		$content ['content_filename'] = 'users/default.php';

		break;

}

//if(!empty($user_session)){
$user_session ['user_action'] = $user_action;

$this->template ['user_action'] = $user_action;

$this->load->vars ( $this->template );

$this->session->set_userdata ( 'user_session', $user_session );

//}


if (is_dir ( $the_active_site_template_dir ) == false) {

	header ( "HTTP/1.1 500 Internal Server Error" );

	show_error ( 'No such template: ' . $the_active_site_template );

	exit ();

}

//var_dump ( $the_active_site_template_dir . $content ['content_filename'] );


//exit ();


if (trim ( $content ['content_filename'] ) != '') {

	if (is_readable ( $the_active_site_template_dir . $content ['content_filename'] ) == true) {

		$this->load->vars ( $this->template );

		$content_filename_pre = $this->load->file ( $the_active_site_template_dir . $content ['content_filename'], true );

		$this->load->vars ( $this->template );

	} else {

		header ( "HTTP/1.1 500 Internal Server Error" );

		show_error ( "File {$content ['content_filename']} is not readable or doesn't exist in the templates directory!" );

		exit ();

	}

}

if ($no_layout == false) {
	if ($content ['content_layout_file'] != '') {

		//$this->template ['title'] = 'adasdsad';
		if (is_readable ( $the_active_site_template_dir . $content ['content_layout_file'] ) == true) {

			$this->load->vars ( $this->template );

			$layout = $this->load->file ( $the_active_site_template_dir . $content ['content_layout_file'], true );

		} elseif (is_readable ( $the_active_site_template_dir . 'default_layout.php' ) == true) {

			$this->load->vars ( $this->template );

			$layout = $this->load->file ( $the_active_site_template_dir . 'default_layout.php', true );

		} else {

			header ( "HTTP/1.1 500 Internal Server Error" );

			show_error ( "Layout file {$content ['content_layout_file']} is not readable or doesn't exist in the templates directory!" );

			exit ();

		}

	} else {
		if (is_readable ( $the_active_site_template_dir . 'users/layout.php' ) == true) {

			$this->load->vars ( $this->template );

			$layout = $this->load->file ( $the_active_site_template_dir . 'users/layout.php', true );

		}
	}
} else {
	$layout = '{content}';
}

if (trim ( $content ['content_filename'] ) != '') {

	if (is_readable ( $the_active_site_template_dir . $content ['content_filename'] ) == true) {

		$this->load->vars ( $this->template );

		$content_filename = $this->load->file ( $the_active_site_template_dir . $content ['content_filename'], true );

		$layout = str_ireplace ( '{content}', $content_filename, $layout );

	//	$layout = str_ireplace ( '{content}', $content_filename_pre, $layout );
	}

}

if (trim ( $content ['content_body'] ) != '') {

	$this->load->vars ( $this->template );

	$layout = str_ireplace ( '{content}', $content ['content_body'], $layout );

}

if (trim ( $taxonomy_data ) != '') {

	$this->load->vars ( $this->template );

	$layout = str_ireplace ( '{content}', $taxonomy_data, $layout );

}

$layout = $this->content_model->applyGlobalTemplateReplaceables ( $layout, $global_template_replaceables = false );

//var_dump($layout);


$opts = array ();
$opts ['no_microwber_tags'] = false;
$opts ['no_remove_div'] = false;
//$opts = array ();


$layout = $this->template_model->parseMicrwoberTags ( $layout, $opts );

$this->output->set_output ( $layout );

?>