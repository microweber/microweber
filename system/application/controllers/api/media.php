<?php

class Media extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		//p($user_session);
		require_once (APPPATH . 'controllers/api/default_constructor.php');
		
		if (CI::model ( 'users' )->is_logged_in () == false) {
			//    exit ( 'Login required' );
		}
	
	}
	
	function nic_upload() {
		$user_id = CI::model ( 'core' )->userId ();
		if (intval ( $user_id ) == 0) {
			exit ( 'Error!' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$status = CI::model ( 'core' )->upload ();
			$status = json_encode ( $status );
			$script = '
        try {
            ' . (($_SERVER ['REQUEST_METHOD'] == 'POST') ? 'top.' : '') . 'nicUploadButton.statusCb(' . ($status) . ');
        } catch(e) { alert(e.message); }
    ';
			
			if ($_POST) {
				echo '<script>' . $script . '</script>';
			} else {
				echo $script;
			}
		}
	
	}
	
	function reorder_media() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$positions = $_POST ['gallery_module_sortable_pics_positions'];
		if (! empty ( $positions )) {
			CI::model ( 'core' )->mediaReOrder ( $positions );
		}
		exit ();
	}
	
	function reorder() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$positions = $_POST ['picture_id'];
		if (! empty ( $positions )) {
			CI::model ( 'core' )->mediaReOrder ( $positions );
		}
		exit ();
	}
	
	function media_delete() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$id = $_POST ['id'];
		if (intval ( $id ) != 0) {
			CI::model ( 'core' )->mediaDelete ( $id );
		}
		exit ();
	}
	
	function media_save() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$id = $_POST ['id'];
		if (intval ( $id ) != 0) {
			CI::model ( 'core' )->mediaSave ( $_POST );
		}
		print intval ( $id );
		exit ();
	}
	
	function media_get_json() {
	
	}
	
	function upload_to_library() {
		$user_id = CI::model ( 'core' )->userId ();
		if (intval ( $user_id ) == 0) {
			exit ( 'Error!' );
		}
		$user_id = is_admin ();
		if ($user_id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		//var_dump ( $_POST );
		//var_dump ( $_FILES );
		if ($_POST) {
			//p ( $_POST );
			//p ( $_FILES );
			if (url_param ( 'for' )) {
				$for = url_param ( 'for' );
			
			}
			
			if (url_param ( 'for_id' )) {
				$id = url_param ( 'for_id' );
			
			}
			
			if (url_param ( 'queue_id' )) {
				$queue_id = url_param ( 'queue_id' );
			
			}
			
			if (url_param ( 'module_id' )) {
				$module_id = url_param ( 'module_id' );
			
			}
			
			if ($_POST ['for']) {
				$for = $_POST ['for'];
			}
			if ($_POST ['for_id']) {
				$id = $_POST ['for_id'];
			}
			if ($_POST ['queue_id']) {
				$queue_id = $_POST ['queue_id'];
			}
			
			$status = CI::model ( 'core' )->upload ( $for, $id, $queue_id, $collection = $module_id );
			//p ( $status );
			print json_encode ( $status );
			exit ();
		}
	
	}
	
	function upload() {
		$user_id = CI::model ( 'core' )->userId ();
		if (intval ( $user_id ) == 0) {
			exit ( 'Error!' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		//var_dump ( $_POST );
		//var_dump ( $_FILES );
		if ($_POST) {
			//p ( $_POST );
			//p ( $_FILES );
			$status = CI::model ( 'core' )->upload ();
			//p ( $status );
			print json_encode ( $status );
			exit ();
		}
	
	}
	
	function upload_base64() {
		$user_id = CI::model ( 'core' )->userId ();
		if (intval ( $user_id ) == 0) {
			exit ( 'Error!' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		$file = ($_POST ['file']);
		$filename = ($_POST ['filename']);
		
		$status = CI::model ( 'core' )->upload_base64 ( $file, $filename );
		//print base64_decode($file);
		$status = p ( $status );
		exit ( $status );
	
	}
	
	function delete_picture() {
		@ob_clean ();
		
		$pic_id = intval ( CI::model ( 'core' )->securityDecryptString ( $_POST ['id'] ) );
		if (intval ( $pic_id ) == 0) {
			exit ( 'Error!' );
		}
		
		$user_id = CI::model ( 'core' )->userId ();
		if (intval ( $user_id ) == 0) {
			exit ( 'Error!' );
		}
		
		$media = CI::model ( 'core' )->mediaGetById ( $pic_id );
		//p($media);
		if (intval ( $media ['created_by'] ) == intval ( $user_id )) {
			//exit('ok');
			CI::model ( 'core' )->mediaDelete ( $media ['id'] );
		} else {
			exit ( 'Error! This picture is not yours!' );
		}
	}
	
	function user_upload_picture() {
		@ob_clean ();
		
		header ( "Content-Type: text/plain" );
		
		$user_id = CI::model ( 'core' )->userId ();
		if (intval ( $user_id ) == 0) {
			exit ( 'Error! You are not logged in.' );
		}
		
		if (! empty ( $_FILES )) {
			$resize_options = array ();
			$resize_options ['width'] = 400;
			$resize_options ['height'] = 400;
			
			$to_table = CI::model ( 'core' )->guessDbTable ();
			$id = CI::model ( 'core' )->guessId ();
			if ($to_table == '') {
				$to_table = 'table_content';
			}
			
			$res = CI::model ( 'core' )->mediaUpload ( $to_table, $id, $queue_id = false, $resize_options );
			$res = json_encode ( $res );
			exit ( $res );
		}
	
	}
	
	function crop_picture_by_id() {
		@ob_clean ();
		$user_id = CI::model ( 'core' )->userId ();
		if (intval ( $user_id ) == 0) {
			exit ( 'Error! You are not logged in.' );
		}
		
		$id = $_POST ['id'];
		
		if ($id > 0) {
			$media = CI::model ( 'core' )->mediaGetById ( $id );
			if (! empty ( $media )) {
				require ('ImageManipulation.php');
				$file_path = MEDIAFILES . 'pictures/original/' . $media ['filename'];
				$objImage = new ImageManipulation ( $file_path );
				if ($objImage->imageok) {
					$objImage->setCrop ( $_POST ['x'], $_POST ['y'], $_POST ['w'], $_POST ['h'] );
					//$objImage->resize(500);
					//$objImage->show();
					$objImage->save ( $file_path );
				} else {
					echo 'Error!';
				}
			}
		}
		
		//	$src = 'flowers.jpg';
		

		//exit ();
		exit ();
		
	//var_dump ( $_POST );
	

	}
	
	function user_get_picture_info() {
		$user_id = CI::model ( 'core' )->userId ();
		if (intval ( $user_id ) == 0) {
			exit ( 'Error! You are not logged in.' );
		}
		
		$media = CI::model ( 'users' )->getUserPictureInfo ( $user_id );
		if (empty ( $media )) {
			exit ( 'no image' );
		} else {
			///	p($media);
			$media = json_encode ( $media );
			exit ( $media );
		}
	
	}
}



