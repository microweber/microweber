<?php

$this->template ['controller_url'] = site_url ( 'users/user_action:content-groups/' );
$this->template ['campaigns_manager_active'] = true;

$id = $this->core_model->getParamFromURL ( 'id' );
$add = $this->core_model->getParamFromURL ( 'add' );

if ($_POST) {
	$errors = array ();
	if ($_POST ['taxonomy_value'] == '') {
		//$errors [] = 'Please enter a title';
		$_POST ['taxonomy_value'] = 'Group-' . date ( "Ymdhis" );
	}
	if (empty ( $errors )) {
		$save = $this->content_model->taxonomySave ( $_POST );
	} else {
		$this->template ['errors'] = $errors;
	}

}

if (($id) == false and $add != 'yes') {
	$user_content = array ();
	$user_content ['taxonomy_type'] = 'group';
	$user_content ['created_by'] = $this->core_model->userId ();
	$groups = $this->content_model->taxonomyGet ( $data = $user_content, $orderby = false, $no_limits = true, $no_cache = false );

	//p($groups);
	$this->template ['groups'] = $groups;

	$content ['content_filename'] = 'users/content_groups.php';
} else {

	$user_content = array ();
	$user_content ['taxonomy_type'] = 'group';
	$user_content ['created_by'] = $this->core_model->userId ();
	$user_content ['id'] = $id;
	$form_values = $this->content_model->taxonomyGet ( $data = $user_content, $orderby = false, $no_limits = true, $no_cache = false );
	if (empty ( $form_values )) {
		$content ['content_filename'] = 'users/content_groups_no_access.php';
	} else {
		$form_values = $form_values [0];
		$this->template ['form_values'] = $form_values;

		$content ['content_filename'] = 'users/content_groups_edit.php';
	}

}
?>