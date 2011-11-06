<?php
$username = CI::model('core')->getParamFromURL ( 'username' );

//print $username;


$action = CI::model('core')->getParamFromURL ( 'action' );

$author = CI::model('users')->getIdByUsername ( $username );
$this->template ['author_id'] = $author;

$user_content = array ();
$user_content ['content_type'] = 'post';
$user_content ['created_by'] = $author;

if ($action == 'trainings') {
	$user_content ['content_subtype'] = 'trainings';
}

if ($action == 'services') {
	$user_content ['content_subtype'] = 'services';
}

if ($action == 'products') {
	$user_content ['content_subtype'] = 'products';
}

if ($action == 'blog') {
	$user_content ['content_subtype'] = 'blog';
}

if ($action == 'gallery') {
	$user_content ['content_subtype'] = 'gallery';
}


//p($user_content);
$user_content = CI::model('content')->contentGetByParams ( $user_content );

//p($user_content);


$this->template ['posts_data'] = $user_content;
$this->template ['posts'] = $user_content ['posts'];
$this->template ['user_content'] = $user_content ['posts'];

$this->load->vars ( $this->template );

$content ['content_filename'] = 'users/userbase/articles.php';
