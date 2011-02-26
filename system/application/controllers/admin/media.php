<?php

class Media extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	}
	
	function index() {
		exit ( 'Media manager is not yet fully ready! Write us at info@ooyes.net with request!' );
	}
	
	function reorderMedia() {
		$positions = $_POST ['gallery_module_sortable_pics_positions'];
		if (! empty ( $positions )) {
			CI::model('core')->mediaReOrder ( $positions );
		}
		exit ();
	}
	
	function mediaDelete() {
		$id = $_POST ['id'];
		if (intval ( $id ) != 0) {
			CI::model('core')->mediaDelete ( $id );
		}
		exit ();
	}
	
	function mediaSave() {
		$id = $_POST ['id'];
		if (intval ( $id ) != 0) {
			CI::model('core')->mediaSave ( $_POST );
		}
		print intval ( $id );
		exit ();
	}
	
	/**
	 * @desc	Upload pictures
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function mediaUploadPictures() {
		
		if (! empty ( $_FILES )) {
			
			$queue_id = CI::model('core')->getParamFromURL ( 'queue_id' , $param_sub_position = false, $skip_ajax = true);
			$to_table = CI::model('core')->getParamFromURL ( 'to_table', $param_sub_position = false, $skip_ajax = true );
			$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id', $param_sub_position = false, $skip_ajax = true );
			if (strval ( $to_table ) == '') {
				exit ( 'Error: please give me the $to_table var in the URL' );
			}
			if (intval ( $to_table_id ) != 0) {
				$queue_id = false;
			}
			
			CI::model('core')->mediaUploadPictures ( $to_table, $to_table_id, $queue_id );
			//CI::model('core')->cacheDeleteAll();
			

			sleep ( 1 );
			echo 1;
		} else {
			echo "1";
		}
		
		exit ();
	}
	
	function mediaUploadFilesIframe() {
		$queue_id = CI::model('core')->getParamFromURL ( 'queue_id' , $param_sub_position = false, $skip_ajax = true);
		$to_table = CI::model('core')->getParamFromURL ( 'to_table' , $param_sub_position = false, $skip_ajax = true);
		$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id' , $param_sub_position = false, $skip_ajax = true);
		
		$this->template ['queue_id'] = $queue_id;
		$this->template ['to_table'] = $to_table;
		$this->template ['to_table_id'] = $to_table_id;
		
		//var_dump ( $media2 );
		

		//$medias = array_merge ( $media1, $media2 );
		//var_dump($medias);
		//$this->template ['medias'] = $medias;
		

		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/media/fileUploadIframe.php', true, true );
		//CI::library('output')->set_output ( $layout );
		exit ( $layout );
	}
	
	function mediaUploadPicturesIframe() {
		$queue_id = CI::model('core')->getParamFromURL ( 'queue_id' , $param_sub_position = false, $skip_ajax = true);
		$to_table = CI::model('core')->getParamFromURL ( 'to_table', $param_sub_position = false, $skip_ajax = true );
		$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id', $param_sub_position = false, $skip_ajax = true );
		//P($queue_id);
		$this->template ['queue_id'] = $queue_id;
		$this->template ['to_table'] = $to_table;
		$this->template ['to_table_id'] = $to_table_id;
		
		
	 
		

		$this->load->vars ( $this->template );
 
		$layout = CI::view ( 'admin/media/picUploadIframe.php', true, true );
		//CI::library('output')->set_output ( $layout );
		exit ( $layout );
	}
	
	function mediaUploadVideosIframe() {
		$queue_id = CI::model('core')->getParamFromURL ( 'queue_id', $param_sub_position = false, $skip_ajax = true );
		$to_table = CI::model('core')->getParamFromURL ( 'to_table', $param_sub_position = false, $skip_ajax = true );
		$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id', $param_sub_position = false, $skip_ajax = true );
		
		$this->template ['queue_id'] = $queue_id;
		$this->template ['to_table'] = $to_table;
		$this->template ['to_table_id'] = $to_table_id;
		
		//var_dump ( $media2 );
		

		//$medias = array_merge ( $media1, $media2 );
		//var_dump($medias);
		//$this->template ['medias'] = $medias;
		

		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/media/videoUploadIframe.php', true, true );
		//CI::library('output')->set_output ( $layout );
		exit ( $layout );
	}
	
	/**
	 * @desc	Upload videos
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function mediaUploadFiles() {
		//var_dump ( $_FILES );
		if (! empty ( $_FILES )) {
			
			if ($_FILES ["file"] ["error"] > 0) {
				echo "Return Code: " . $_FILES ["file"] ["error"] . "<br />";
			}
			
			$queue_id = CI::model('core')->getParamFromURL ( 'queue_id', $param_sub_position = false, $skip_ajax = true );
			$to_table = CI::model('core')->getParamFromURL ( 'to_table', $param_sub_position = false, $skip_ajax = true );
			$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id', $param_sub_position = false, $skip_ajax = true );
			if (strval ( $to_table ) == '') {
				exit ( 'Error: please give me the $to_table var in the URL' );
			}
			if (intval ( $to_table_id ) != 0) {
				$queue_id = false;
			}
			
			//	var_dump( $to_table, $to_table_id, $queue_id);
			

			CI::model('core')->mediaUploadFiles ( $to_table, $to_table_id, $queue_id );
			CI::model('core')->cacheDeleteAll ();
			
		//exit ( 1 );
		} else {
			//echo "1";
			exit ();
		}
		exit ();
		//exit ( 'ok' );
	}
	
	/**
	 * @desc	Upload videos
	 * @author	Peter Ivanov
	 * @version	1.0
	 * @since	1.0
	 */
	function mediaUploadVideos() {
		//var_dump ( $_FILES );
		if (! empty ( $_FILES )) {
			
			if ($_FILES ["file"] ["error"] > 0) {
				echo "Return Code: " . $_FILES ["file"] ["error"] . "<br />";
			}
			
			$queue_id = CI::model('core')->getParamFromURL ( 'queue_id', $param_sub_position = false, $skip_ajax = true );
			$to_table = CI::model('core')->getParamFromURL ( 'to_table' , $param_sub_position = false, $skip_ajax = true);
			$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id', $param_sub_position = false, $skip_ajax = true );
			if (strval ( $to_table ) == '') {
				exit ( 'Error: please give me the $to_table var in the URL' );
			}
			if (intval ( $to_table_id ) != 0) {
				$queue_id = false;
			}
			
			//	var_dump( $to_table, $to_table_id, $queue_id);
			

			CI::model('core')->mediaUploadVideos ( $to_table, $to_table_id, $queue_id );
			CI::model('core')->cacheDeleteAll ();
			
		//exit ( 1 );
		} else {
			//echo "1";
			exit ();
		}
		exit ();
		//exit ( 'ok' );
	}
	
	function contentMediaPicturesList() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$queue_id = CI::model('core')->getParamFromURL ( 'queue_id', false, true );
		$to_table = CI::model('core')->getParamFromURL ( 'to_table', false, true );
		$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id', false, true );
		//var_Dump($to_table_id);
		//	var_dump($queue_id,$to_table,$to_table_id);
		if ($queue_id != false) {
			$media1 = CI::model('core')->mediaGet ( $to_table, false, $media_type = 'picture', $order = "ASC", $queue_id, $no_cache = true, false );
		 
			$this->template ['media1'] = $media1 ['pictures'];
		}
		
		// 	var_dump ( $media1 );
		if ($to_table_id != false) {
			
			$media2 = CI::model('core')->mediaGet ( $to_table, $to_table_id, $media_type = 'picture', $order = "ASC", $queue_id = false, $no_cache = true, false );
			 
			$this->template ['media2'] = $media2 ['pictures'];
		}
	 	
	 	
		

		$this->load->vars ( $this->template );
		$try_view = TEMPLATE_DIR . 'admin/media/contentMediaPicturesList.php';
		//var_dump($try_view);
		if (is_file ( $try_view ) and is_readable ( $try_view )) {
			//$primarycontent = CI::view ( $try_view, true, true );
			$layout = $this->load->file ( $try_view, true );
			if (trim ( $layout ) == '') {
				print 'admin/media/contentMediaPicturesList';
				$layout = CI::view ( 'admin/media/contentMediaPicturesList', true, true );
			} else {
				var_dump ( $try_view );
			
			}
		
		} else {
			
			$layout = CI::view ( 'admin/media/contentMediaPicturesList', true, true );
			//	var_dump('asd');
		}
		
		//CI::library('output')->set_output ( $layout );
		exit ( $layout );
	
	}
	
	function contentMediaFilesList() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		global $cms_db_tables;
		$table = $cms_db_tables ['table_media'];
		
		$queue_id = CI::model('core')->getParamFromURL ( 'queue_id', false, true );
		$to_table = CI::model('core')->getParamFromURL ( 'to_table' , false, true);
		$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id', false, true );
		//var_dump($queue_id,$to_table,$to_table_id);
		

		//var_dump($_REQUEST);
		//p($q);
		

		if (strval ( trim ( $queue_id ) != '' )) {
			
			$q = "SELECT id FROM $table WHERE to_table = '$to_table' AND queue_id = '$queue_id' 

			AND  media_type = 'file' 
			AND to_table_id IS NULL AND ID is not null ORDER BY media_order";
			$q = CI::model('core')->dbQuery ( $q );
			$ids = CI::model('core')->dbExtractIdsFromArray ( $q );
			
			if (! empty ( $ids )) {
				
				$media_get = array ();
				$media_get ['queue_id'] = trim ( $queue_id );
				$media_get ['to_table'] = $to_table;
				//$media_get ['to_table_id'] = $to_table_id;
				$media1 = CI::model('core')->mediaGet2 ( false, false, $ids );
				//p($media1);
				$this->template ['files'] = $media1 ['files'];
			}
		} else {
			$media1 = array ();
		}
		
		//var_dump ( $media1 );
		if (intval ( $to_table_id ) != 0) {
			$media_get = array ();
			//$media_get ['queue_id'] = $queue_id;
			$media_get ['to_table'] = $to_table;
			$media_get ['to_table_id'] = $to_table_id;
			
			$q = "SELECT id FROM $table WHERE to_table = '$to_table'  
			
			AND  media_type = 'file' and
			to_table_id = '$to_table_id' AND ID is not null ORDER BY media_order";
			
			//var_dump($q);
			

			$q = CI::model('core')->dbQuery ( $q );
			$ids = CI::model('core')->dbExtractIdsFromArray ( $q );
			if (! empty ( $ids )) {
				
				$media2 = CI::model('core')->mediaGet2 ( false, false, $ids );
				
				//p($media2);
				

				$this->template ['files'] = $media2 ['files'];
			}
		} else {
			$media2 = array ();
		}
		
		//var_dump ( $media2 );
		//var_dump ( $media1 );
		

		if (! empty ( $media1 )) {
			//$media1 = $media1 ['videos'];
		}
		
		if (! empty ( $media2 )) {
			//$media2 = $media2 ['videos'];
		}
		//var_dump( $media1, $media2);
		

		$videos = @array_merge ( $media1, $media2 );
		//var_dump ( $videos );
		$videos = $videos ['files'];
		//@array_unique ( $videos );
		

		//$this->template ['videos'] = $videos;
		

		//$medias = array_merge ( $media1, $media2 );
		//var_dump($medias);
		//$this->template ['medias'] = $medias;
		

		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/media/contentMediaFilesList', true, true );
		//CI::library('output')->set_output ( $layout );
		exit ( $layout );
	
	}
	
	function contentMediaVideosList() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		global $cms_db_tables;
		$table = $cms_db_tables ['table_media'];
		
		$queue_id = CI::model('core')->getParamFromURL ( 'queue_id', false, true );
		$to_table = CI::model('core')->getParamFromURL ( 'to_table', false, true );
		$to_table_id = CI::model('core')->getParamFromURL ( 'to_table_id', false, true );
		//var_dump($queue_id,$to_table,$to_table_id);
		

		//p($q);
		

		if (strval ( trim ( $queue_id ) != '' )) {
			
			$q = "SELECT id FROM $table WHERE to_table = '$to_table' AND queue_id = '$queue_id'  AND to_table_id IS NULL AND ID is not null ORDER BY media_order";
			$q = CI::model('core')->dbQuery ( $q );
			$ids = CI::model('core')->dbExtractIdsFromArray ( $q );
			
			if (! empty ( $ids )) {
				
				$media_get = array ();
				$media_get ['queue_id'] = trim ( $queue_id );
				$media_get ['to_table'] = $to_table;
				//$media_get ['to_table_id'] = $to_table_id;
				$media1 = CI::model('core')->mediaGet2 ( false, false, $ids );
				//p($media1);
				$this->template ['videos'] = $media1 ['videos'];
			}
		} else {
			$media1 = array ();
		}
		
		//var_dump ( $media1 );
		if (intval ( $to_table_id ) != 0) {
			$media_get = array ();
			//$media_get ['queue_id'] = $queue_id;
			$media_get ['to_table'] = $to_table;
			$media_get ['to_table_id'] = $to_table_id;
			
			$q = "SELECT id FROM $table WHERE to_table = '$to_table' AND to_table_id = '$to_table_id' AND ID is not null ORDER BY media_order";
			$q = CI::model('core')->dbQuery ( $q );
			$ids = CI::model('core')->dbExtractIdsFromArray ( $q );
			if (! empty ( $ids )) {
				
				$media2 = CI::model('core')->mediaGet2 ( false, false, $ids );
				
				//p($media2);
				

				$this->template ['videos'] = $media2 ['videos'];
			}
		} else {
			$media2 = array ();
		}
		
		//var_dump ( $media2 );
		//var_dump ( $media1 );
		

		if (! empty ( $media1 )) {
			//$media1 = $media1 ['videos'];
		}
		
		if (! empty ( $media2 )) {
			//$media2 = $media2 ['videos'];
		}
		//var_dump( $media1, $media2);
		

		$videos = @array_merge ( $media1, $media2 );
		//var_dump ( $videos );
		$videos = $videos ['videos'];
		//@array_unique ( $videos );
		

		//$this->template ['videos'] = $videos;
		

		//$medias = array_merge ( $media1, $media2 );
		//var_dump($medias);
		//$this->template ['medias'] = $medias;
		

		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/media/contentMediaVideosList', true, true );
		//CI::library('output')->set_output ( $layout );
		exit ( $layout );
	
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */