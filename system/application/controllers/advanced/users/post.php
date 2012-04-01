<?php

$content_id = CI::model('core')->getParamFromURL ( 'id' );
$categories_ids_to_remove = array ();
$categories_ids_to_remove ['taxonomy_type'] = 'category';
$categories_ids_to_remove ['users_can_create_content'] = 'n';
$categories_ids_to_remove = CI::model('taxonomy')->getIds ( $data = $categories_ids_to_remove, $orderby = false );
$this->template ['categories_ids_to_remove'] = $categories_ids_to_remove;

$categories_ids_to_add = array ();
$categories_ids_to_add ['taxonomy_type'] = 'category';
$categories_ids_to_add ['users_can_create_content'] = 'y';
$categories_ids_to_add = CI::model('taxonomy')->getIds ( $data = $categories_ids_to_add, $orderby = false );
$this->template ['categories_ids_to_add'] = $categories_ids_to_add;

//	p($categories_ids_to_remove);
$check_is_permisiions_error = false;

if (intval ( $content_id ) != 0) {

	$get_id = array ();

	$get_id ['id'] = $content_id;
	$get_id ['created_by'] = $user_session ['user_id'];
	$get_id ['content_type'] = 'post';
	$get_id = CI::model('content')->getContent ( $get_id );

	$get_id = $get_id [0];

	//var_dump($get_id);
	if (! empty ( $get_id )) {

		if ($get_id ['created_by'] != $user_session ['user_id']) {

			//var_dump($get_id ['created_by'], $user_session ['user_id']);
			redirect ( 'users' );

		} else {
			//	p($get_id, 1);
			$this->template ['form_values'] = $get_id;

		}

	} else {

		redirect ( 'users' );

	}

}

if ($_POST) {

	$errors = array ();
	$categories = $_POST ['taxonomy_categories'];

	if (! empty ( $categories )) {

		foreach ( $categories as $cat ) {
			$parrent_cats = CI::model('taxonomy')->getParents ( $cat );

			foreach ( $parrent_cats as $par_cat ) {
				$categories [] = $par_cat;
			}

		}
		$categories = array_unique ( $categories );
	}

	$category = $categories [count ( $categories ) - 1];
	if (! empty ( $categories )) {
		$i = 0;
		foreach ( $categories as $cat ) {
			if (! empty ( $categories_ids_to_remove )) {
				if (in_array ( $cat, $categories_ids_to_remove ) == true) {
					unset ( $categories [$i] );
				}
			}
			$i ++;
		}
	}

	if ( (! empty ( $categories_ids_to_remove )) and (in_array ( $category, $categories_ids_to_remove ) == true)     ) {
		exit ( 'WOW invalid category! How this can be?' );
		//error
	} else {

		$check_title = array ();

		if (trim ( strval ( $_POST ['content_title'] ) ) == '') {
			$errors ['content_title'] = "Please enter title";
		}

		if (trim ( strval ( $_POST ['content_description'] ) ) == '') {
			//$errors ['content_description'] = "Please enter description";
		}

		$check_title ['content_title'] = $_POST ['content_title'];

		$check_title ['content_type'] = 'post';

		$check_title = CI::model('content')->getContent ( $check_title, $orderby = false, $limit = false, $count_only = false );

		$check_title_error = false;

		if (! empty ( $check_title )) {

			if ($_POST ['id']) {

				if ($check_title [0] ['id'] != $_POST ['id']) {

				//$check_title_error = true;


				} else {

				//$check_title_error = false;


				}

			} else {

			//$check_title_error = true;


			}

		//
		} else {

		}

		if ($check_title_error == true) {

		//errror
		} else {

			$taxonomy_categories = array ($category );

			$taxonomy = CI::model('taxonomy')->getParents ( $category );

			if (! empty ( $taxonomy )) {

				foreach ( $taxonomy as $i ) {

					$taxonomy_categories [] = $i;

				}

			}

			//var_dump($taxonomy);
			//exit;
			$to_save = $_POST;

			$to_save ['content_type'] = 'post';

			if (empty ( $categories )) {
				$errors ['taxonomy_categories'] = "Please choose at least one category";
			}
			$categories = array_reverse ( $categories );
			$to_save ['taxonomy_categories'] = $categories;

			$parent_page = false;

			foreach ( $categories as $cat ) {
				if (empty ( $parent_page )) {
					$parent_page = CI::model('content')->contentsGetTheLastBlogSectionForCategory ( $cat );
				}

			}

			if (! empty ( $categories )) {
				if (empty ( $parent_page )) {
					//$errors [] = "Invalid category. This category doesn't have defined section from the admin!";
				//errror
				}
			}
			//var_dump($errors);
			if (empty ( $errors )) {
				$to_save ['content_parent'] = $parent_page ['id'];
				$to_save ['is_home'] = 'n';
				$to_save ['content_type'] = 'post';
				//								p($to_save);
				//p($to_save);


				$saved = CI::model('content')->saveContent ( $to_save );

				// log to user activities
				/*if (!$to_save['id']) {
									$activity = array(
										'user_id' => $user['id'],
										'type' => 'new_post',
										'message'=> CI::model('users')->buildActivityMessage(
											'new_post',
											array(
												'username' => $user['username'],
												'content_url' => CI::model('content')->getContentURLByIdAndCache($saved),
												'content_title' => $to_save['content_title'],
											)
										)
									);

									CI::model('core')->saveData(TABLE_PREFIX.'users_activities', $activity);
								}*/

				//p($to_save);
				redirect ( 'users/user_action:posts/type:all' );

			} else {
				//p($errors);
				$this->template ['form_values'] = $_POST;
				$this->template ['form_errors'] = $errors;
			}

		}

	}
}
$this->load->vars ( $this->template );
$user_session ['user_action'] = $user_action;
$type = CI::model('core')->getParamFromURL ( 'type' );

if ($type == 'form') {
	$this->template ['forms_manager_active'] = true;
	//$content ['content_filename'] = 'users/posts_forms_view.php';
//$content ['content_filename'] = 'users/post_forms_view.php';
} else {
	$this->template ['pages_manager_active'] = true;
	//$content ['content_filename'] = 'users/post.php';
//$content ['content_filename'] = 'users/posts.php';


}

$possible_type_view = TEMPLATES_DIR . 'users/post_' . $type . '.php';
if (is_file ( $possible_type_view )) {
	$content ['content_filename'] = 'users/post_' . $type . '.php';
} else {
	$content ['content_filename'] = 'users/post.php';
}

?>