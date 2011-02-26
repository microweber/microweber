<?php

$this->template ['controller_url'] = site_url ( 'users/user_action:posts/' );

$user_content = array ();

$user_content ['content_type'] = 'post';

$type = CI::model('core')->getParamFromURL ( 'type' );

if ($type == 'trainings') {
	$user_content ['content_subtype'] = 'trainings';
}

if ($type == 'services') {
	$user_content ['content_subtype'] = 'services';
}

if ($type == 'products') {
	$user_content ['content_subtype'] = 'products';
}

if ($type == 'blog') {
	$user_content ['content_subtype'] = 'blog';
}

if ($type == 'gallery') {
	$user_content ['content_subtype'] = 'gallery';
}

$user_content ['created_by'] = CI::model('core')->userId ();

$user_content = CI::model('content')->contentGetByParams ( $user_content );

//p($user_content);


//$user_content = CI::model('content')->getContent ( $user_content, $orderby = array ('updated_on', 'DESC' ), $limit = false, $count_only = false );


//	var_dump ( $user_content );
$this->template ['posts_data'] = $user_content;
$this->template ['posts'] = $user_content ['posts'];
$this->template ['user_content'] = $user_content ['posts'];

$user_session ['user_action'] = $user_action;

//$this->load->vars ( $this->template );
$small_posts_view = CI::model('core')->getParamFromURL ( 'tinymce_posts_view' );
$this->load->vars ( $this->template );
if ($small_posts_view == 'yes') {
	$params = array ();
	$params ['user_action'] = 'posts';
	$params ['tinymce_posts_view'] = 'yes';
	$params ['type'] = 'inherit';
	$url = CI::model('core')->urlConstruct ( $base_url = site_url ( 'users' ), $params );
	$this->template ['controller_url'] = $url; //user_action:posts/tinymce_posts_view:yes' ) . '/';


	if ($_POST) {
		$data = json_decode ( $_POST ['data'], 1 );

		$this->template ['data'] = $data;
		$this->template ['display_posts'] = 'yes';
	}
	$this->load->vars ( $this->template );
	$content ['content_layout_file'] = 'empty_layout.php';
	$content ['content_filename'] = 'users/posts_tinymce_view.php';
} else {

	$try_view = CI::model('core')->getParamFromURL ( 'view' );
	$try_type = CI::model('core')->getParamFromURL ( 'type' );
	$try_layout = CI::model('core')->getParamFromURL ( 'layout' );

	if ($try_type == 'form') {
		$this->template ['forms_manager_active'] = true;

	} else {
		$this->template ['pages_manager_active'] = true;

	}

	if ($try_type != false) {
		$type = '_' . $try_type;
	} else {
		$type = false;
	}

	if ($try_layout != false) {
		$try_layout = '' . $try_layout;
	} else {
		$try_layout = false;
	}
	$content ['content_layout_file'] = trim ( $try_layout ) . '_layout.php';

	if ($try_view != false) {
		$view = '_' . $try_view;
	} else {
		$view = false;
	}

	$possible_type_view = TEMPLATES_DIR . 'users/posts' . $type . $view . '.php';
	//p($possible_type_view);
	if (is_file ( $possible_type_view )) {

		$content ['content_filename'] = 'users/posts' . $type . $view . '.php';
	} else {
		$possible_type_view =  'users/posts' . $view . '.php';
		if (is_file ( $possible_type_view )) {
			$content ['content_filename'] = 'users/posts' . $type . $view . '.php';
		} else {
			$content ['content_filename'] = 'users/posts' .  '.php';
		}

	}

}

?>