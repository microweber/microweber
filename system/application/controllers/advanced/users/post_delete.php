<?php

$content_id = CI::model('core')->getParamFromURL ( 'id' );

$check_is_permisiions_error = false;

if (intval ( $content_id ) != 0) {
	
	$get_id = array ();
	
	$get_id ['id'] = $content_id;
	
	$get_id = CI::model('content')->getContent ( $get_id );
	
	$get_id = $get_id [0];
	
	//var_dump($get_id); 
	if (! empty ( $get_id )) {
		
		if ($get_id ['created_by'] != $user_session ['user_id']) {
			
			//var_dump($get_id ['created_by'], $user_session ['user_id']);
			//redirect ( 'users/posts' );
			exit ( 'error' );
		} else {
			
			//$this->template ['form_values'] = $get_id;
			

			//var_dump($content_id);
			CI::model('content')->deleteContent ( $content_id );
			exit ( 'ok' );
			//redirect ( 'users/posts' );
		

		}
	
	} else {
		exit ( 'error' );
		//redirect ( 'users/posts' );
	

	}

}

?>