<?php

class Main extends Controller {

	function __construct() {

		parent::Controller ();

		require_once (APPPATH . 'controllers/default_constructor.php');

	}

	function index() {

		print 'test';

	}
		function captcha() {
 
		require (APPPATH . 'controllers/captcha.php');
		exit();

	}

	function cronjobs() {

		ob_clean ();

		header ( "Content-Type: text/plain; charset=UTF-8" );

		$grbber = new rss_grabber_model ( );

		$what = $grbber->processNumberOfFeeds ( 1 );

		if ($what == false) {

			CI::model('content')->content_pingServersWithNewContent ();

		//sleep ( 5 );


		//CI::model('content')->content_cleanUpGarbageFromRss ();


		//sleep ( 5 );


		//CI::model('content')->content_RemapExternalLinkFromRssWithInternal ();


		}

	}

	function set_cookie() {

		$name = $_POST ['cookie_name'];

		$val = $_POST ['cookie_value'];

		if ($name and $val) {

			setcookie ( $name, $val );

		}

		exit ();

	}

	function set_session_vars() {

		$name = $_POST ['the_var'];

		$val = $_POST ['the_val'];

		if ($name and $val) {

			CI::library('session')->set_userdata ( $name, $val );

		}

		exit ();

	}

	function rss_comments() {

		ob_clean ();

		header ( "Content-Type: text/xml; charset=UTF-8" );

		header ( "Content-Type: application/rss+xml" );

		$data = array ();

		$categories = CI::model('core')->getParamFromURL ( 'categories' );
		if (trim ( $categories ) != '') {

			$categories = explode ( ',', $categories );
			if (! empty ( $categories )) {
				$data ['selected_categories'] = $categories;
			}
		}
		$post = CI::model('core')->getParamFromURL ( 'post' );
		if (intval ( $post ) != 0) {
			$data ['id'] = intval ( $post );

		}

		$data ['content_type'] = 'post';

		$data ['is_active'] = 'y';

		$orderby = array (0 => 'updated_on', 1 => 'DESC' );

		$limit = array (0 => 0, 1 => 20 );

		//	function getContent($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false) {


		$posts = CI::model('content')->getContent ( $data, $orderby, $limit, false, true );
		$post = $posts [0];
		if (! empty ( $post )) {
			$comments = array ();
			$comments ['to_table'] = 'table_content';
			$comments ['to_table_id'] = $post ['id'];
			$comments = CI::model('comments')->commentsGet ( $comments );
			$this->template ['post'] = $post;
		}

		//	var_dump($posts);
		CI::helper ( 'url' );

		$this->template ['comments'] = $comments;

		$this->load->vars ( $this->template );

		$layout = CI::view ( 'rss_comments.php', true, true );

		CI::library('output')->set_output ( $layout );

	/*
		* $newline = "\n";
		* echo '<?xml version="1.0" encoding="UTF-8" ?>
' . $newline . '
<rss version="2.0">' . $newline;
  echo '
  <channel>' . $newline;

    foreach ( $posts as $item ) {
    echo '<item>' . $newline;
    echo '
    <title>' . $item ['content_title'] . '</title>
    ' . $newline;
    echo '
    <link>
    ' . CI::model('content')->contentGetHrefForPostId ( $item ['id'] ) . '
    </link>
    ' . $newline;
    echo '
    <description>' . $item ['content_meta_description'] . '</description>
    ' . $newline;
    echo '</item>' . $newline;
    }
    echo '</channel>
  ' . $newline;
  echo '</rss>
' . $newline;
		*/

	}

	function rss() {

		ob_clean ();

		header ( "Content-Type: text/xml; charset=UTF-8" );

		header ( "Content-Type: application/rss+xml" );

		$data = array ();

		$categories = CI::model('core')->getParamFromURL ( 'categories' );
		if (trim ( $categories ) != '') {

			$categories = explode ( ',', $categories );
			if (! empty ( $categories )) {
				$data ['selected_categories'] = $categories;
			}
		}
		$created_by = CI::model('core')->getParamFromURL ( 'author' );
		if (intval ( $created_by ) != 0) {
			$data ['created_by'] = $created_by;

		}

		$data ['content_type'] = 'post';

		$data ['is_active'] = 'y';

		$orderby = array (0 => 'updated_on', 1 => 'DESC' );

		$limit = array (0 => 0, 1 => 20 );

		//	function getContent($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false) {


		$posts = CI::model('content')->getContent ( $data, $orderby, $limit, false, true );

		//	var_dump($posts);
		CI::helper ( 'url' );

		$this->template ['posts'] = $posts;

		$this->load->vars ( $this->template );

		$layout = CI::view ( 'rss.php', true, true );

		CI::library('output')->set_output ( $layout );

	/*
		* $newline = "\n";
		* echo '<?xml version="1.0" encoding="UTF-8" ?>
' . $newline . '
<rss version="2.0">' . $newline;
  echo '
  <channel>' . $newline;

    foreach ( $posts as $item ) {
    echo '<item>' . $newline;
    echo '
    <title>' . $item ['content_title'] . '</title>
    ' . $newline;
    echo '
    <link>
    ' . CI::model('content')->contentGetHrefForPostId ( $item ['id'] ) . '
    </link>
    ' . $newline;
    echo '
    <description>' . $item ['content_meta_description'] . '</description>
    ' . $newline;
    echo '</item>' . $newline;
    }
    echo '</channel>
  ' . $newline;
  echo '</rss>
' . $newline;
		*/

	}

	function bb_sync() {
		CI::helper ( 'url' );
		global $cms_db_tables;

		$table = $cms_db_tables ['table_taxonomy'];

		$table2 = 'bb_forums';

		$sitemaps_count = false;

		$q = "select * from $table  where taxonomy_type='category' and users_can_create_content='y' ";

		$q = CI::model('core')->dbQuery ( $q );

		$data_to_save_options ['use_this_field_for_id'] = 'forum_id';
		foreach ( $q as $item ) {
$skip = false;
			if (intval ( $item ['parent_id'] ) == 91) {
				$skip = false;
				$item ['parent_id'] = 0;
			} else {
				//
			}
			if (intval ( $item ['id'] ) == 91) {
				$skip = true;
			}



			$to_save = array ();
			$to_save ['forum_id'] = $item ['id'];
			$to_save ['forum_name'] = $item ['taxonomy_value'];
			$to_save ['forum_slug'] = url_title ( $item ['taxonomy_value'] );

			$to_save ['forum_parent'] = ($item ['parent_id']);

			//CI::model('core')-> saveData($table2, $to_save, $data_to_save_options);


			if ($skip == false) {

				CI::db()->query ( "REPLACE
                             INTO {$table2}
                              SET forum_id              = ?,
                                  forum_name      = ?,
                                  forum_slug       = ?,
                                  forum_parent   = ?
                                  ",

				array ($item ['id'], $item ['taxonomy_value'], url_title ( $item ['taxonomy_value'] ), $item ['parent_id'] ) );

			}

			p ( $to_save );
		}
		exit ( 'Yes!' );

	}

	function comments_post() {

		if ($_POST) {

			if (CI::model('core')->optionsGetByKey ( 'require_login_to_comment' ) == 'y') {
				/*$user_session = CI::library('session')->userdata ( 'user_session' );
				if (strval ( $user_session ['is_logged'] ) != 'yes') {
					exit ( 'Error: You must be logged in. Your comment was not posted.' );
				}*/

			}

		//	$_POST ['to_table_id'] = base64_decode ( $_POST ['to_table_id'] );
		//	$_POST ['to_table'] = base64_decode ( $_POST ['to_table'] );

			$_POST ['to_table_id'] = CI::model('core')->securityDecryptString ( $_POST ['to_table_id'] );
			$_POST ['to_table'] = CI::model('core')->securityDecryptString ( $_POST ['to_table'] );

			if (intval ( $_POST ['to_table_id'] ) == 0) {
				exit ( '1' );
			}

			if (($_POST ['to_table']) == '') {
				exit ( '2' );
			}

			$save = CI::model('comments')->commentsSave ( $_POST );



			print $save;

		//var_dump ( $_POST );


		}

	//	CI::model('core')->cacheDeleteAll ();

	}

	function votes_cast() {

		ob_clean ();

		if ($_POST) {

			$_POST ['to_table_id'] = base64_decode ( $_POST ['tt'] );
			$_POST ['to_table'] = base64_decode ( $_POST ['t'] );

			if (intval ( $_POST ['to_table_id'] ) == 0) {
				exit ( '1' );
			}

			if (($_POST ['to_table']) == '') {
				exit ( '2' );
			}

			$save = CI::model('votes')->votesCast ( $_POST ['to_table'], $_POST ['to_table_id'] );

			if ($save == true) {

				// send user notification


				$user = CI::library('session')->userdata ( 'user' );

				$content = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'content', array (array ('id', $_POST ['to_table_id'] ) ) );
				$content = $content [0];

				$author = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'users', array (array ('id', $content ['created_by'] ) ) );
				$author = $author [0];

				$notification = array ('from_user' => $user ['id'], 'to_user' => $author ['id'], 'type' => 'vote_on_post', 'message_params' => array ('content_url' => CI::model('content')->getContentURLByIdAndCache ( $content ['id'] ), 'content_title' => $content ['content_title'] ) );

				CI::model('users')->sendNotification ( $notification );

				exit ( 'yes' );

			} else {

				exit ( 'no' );

			}

		//print 1;
		} else {

			exit ( 'no votes casted!' );

		}

	}

	function clearcache() {

	//	ob_clean ();

		CI::model('core')->cacheDeleteAll ();

		exit ( '1' );

	}

	function ajax_helpers_taxonomy_get_taxonomy_values_for_taxonomy_serialized_id_and_return_string() {

		ob_clean ();

		$data_to_work = $_REQUEST ['data_to_work'];

		if ($data_to_work) {

			$data_to_work = explode ( ',', $data_to_work );

			$taxonomy_values = array ();

			foreacH ( $data_to_work as $item ) {

				$taxonomy = CI::model('taxonomy')->getSingleItem ( $item );

				$taxonomy_values [] = $taxonomy ['taxonomy_value'];

			}

			$taxonomy_values_implode = implode ( ', ', $taxonomy_values );

		}

		print $taxonomy_values_implode;

		exit ();

	}

	function ajax_helpers_is_valid_url() {

		ob_clean ();

	 

		$data_to_validate = $_REQUEST ['data_to_validate'];

		$link = parse_url ( $data_to_validate );

		if ($link ['host'] == '') {

			$data_to_validate = trim ( $link ['path'] );

		} else {

			$data_to_validate = trim ( $link ['host'] );

		}

		 

		if (strval ( $data_to_validate ) == '') {

			exit ( 'no' );

		} else {

			$data_to_validate = str_ireplace ( 'http://', '', $data_to_validate );

			$data_to_validate = str_ireplace ( 'https://', '', $data_to_validate );

			$data_to_validate = str_ireplace ( 'ftp://', '', $data_to_validate );

			$data_to_validate = str_ireplace ( 'ftps://', '', $data_to_validate );

			$data_to_validate = str_ireplace ( 'samba://', '', $data_to_validate );

			$data_to_validate = str_ireplace ( 'edk://', '', $data_to_validate );

		}

	exit ( 'yes' );
		/*if ($validator->isValid ( $data_to_validate )) {

			exit ( 'yes' );

		} else {

			exit ( 'no' );

		}*/

		exit ( 'no' );

	}

	function mailform_send() {

		$only_valiudation = false;

		$only_valiudation_check = CI::model('core')->getParamFromURL ( 'validate' );

		if ($only_valiudation_check == 'yes') {

			$only_valiudation = true;

		} else {

			$only_valiudation = false;

		}

		$subject = CI::model('core')->optionsGetByKey ( 'mailform_subject' );

		$subject = $subject . ' ' . $_REQUEST ['subject'];

		//$headers.='From: ' . $_REQUEST['Name'] . ' <' . $_REQUEST['email'] . '>';


		#$headers .= "\r\n";


		$headers .= "Content-type: text/plain; charset=utf-8\r\n";

		$headers .= "Content-Transfer-Encoding: quoted-printable\r\n";

		$headers .= "MIME-Version: 1.0\r\n";

		if (strval ( $_REQUEST ['email'] ) == '') {

			$_REQUEST ['email'] = 'unknown@ooyes.net';

		}

		$headers .= "From: {$_REQUEST['email']}" . "\r\n" . #    "Reply-To: webmaster@example.com" . "\r\n" .


		"Reply-To: {$_REQUEST['email']}" . "\r\n" . 'X-Mailer: Microweber (Microweber.com) - PHP/' . phpversion ();

		foreach ( $_POST as $k => $v ) {

			$what = str_replace ( '_', ' ', $k );

			$what = str_replace ( '-', ' ', $k );

			/*			$what = str_replace ( 'view', ' ', $what );

			$what = str_replace ( 'startday', 'Day ', $what );

			$what = str_replace ( 'startmonth', 'Month', $what );

			$what = str_replace ( 'startyear', 'Year', $what );

			$what = str_replace ( 'starthour', 'Start hour', $what );

			$what = str_replace ( 'endhour', 'End hour', $what );*/

			$message = $message . $what . ': ' . $v . "\r\n";

		}

		$message = $message . "\r\n";

		//$message = $message . 'City: ' . $city_name . "\r\n";


		$message = $message . 'IP: ' . $_SERVER ["REMOTE_ADDR"] . "\r\n";

		$message = $message . 'Date-Time: ' . date ( "Y-m-d H:i:s" ) . "\r\n";

		$message = $message . 'REF: ' . $_SERVER ["HTTP_REFERER"] . "\r\n";

		// $sendto = 'alexander.raikov@gmail.com';


		//$sendto = $the_city ['email'];


//		if ($_REQUEST ['cptch'] == '4') {
//
//			$send_me_the_email = true;
//
//		} else {
//
//			$send_me_the_email = false;
//
//		}
//
//		if ($only_valiudation == true) {
//
//			if ($_REQUEST ['cptch'] != '4') {
//
//				exit ( 'no' );
//
//			}
//
//			if ($_REQUEST ['cptch'] == '4') {
//
//				exit ( 'yes' );
//
//			}
//
//		}

		if ($_POST) {

			if ($only_valiudation == false) {

				$sendto = CI::model('core')->optionsGetByKey ( 'mailform_to' );

				//$sendto = 'boksiora@gmail.com';


				$sendto = explode ( ',', $sendto );

				if (! empty ( $sendto )) {

					foreach ( $sendto as $to ) {

						$to = trim ( $to );

						mail ( $to, $subject, $message, $headers );

					//UTF8_mail($_REQUEST['email'],$to,$subject,$message,$cc="",$bcc="");


					}

					exit ( '1' );

				} else {

					exit ( 'Nowhere to send (But how is that possible?), please define option with the key mailform_to from the admin panel and put your email there!' );

				}

			}

		}

	//$message.="\r\nMessage:" . $_REQUEST['message'];


	}

	function mailform_send2() {

		$POST_MAX_SIZE = ini_get ( 'post_max_size' );

		$unit = strtoupper ( substr ( $POST_MAX_SIZE, - 1 ) );

		$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

		$save_path = USERFILES . 'uploads/'; // The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)

		@mkdir ( $save_path );

		$upload_name = 'Filedata'; // change this accordingly

		$max_file_size_in_bytes = 2147483647; // 2GB in bytes

		$whitelist = array ('jpg', 'png', 'gif', 'jpeg' ); // Allowed file extensions

		$backlist = array ('php', 'php3', 'php4', 'phtml', 'exe', 'sh', 'cgi', 'pl', 'so', 'htaccess', 'dmg' ); // Restrict file extensions

		$valid_chars_regex = 'A-Za-z0-9_-\s '; // Characters allowed in the file name (in a Regular Expression format)

		$file_name = '';

		$file_extension = '';

		$to_attach = array ();

		if ($_FILES) {

			foreach ( $_FILES as $k => $v ) {

				// Validate file name (for our purposes we'll just remove invalid characters)

				$file_name = preg_replace ( '/[^' . $valid_chars_regex . ']|\.+$/i', '', strtolower ( basename ( $_FILES [$k] ['name'] ) ) );

				$file_name = md5 ( $file_name . time () );

				if (in_array ( end ( explode ( '.', $file_name ) ), $backlist )) {

				} else {

					// Verify! Upload the file

					if (is_file ( $save_path . $file_name )) {

						$file_name = date ( "Y-m-d-H-i-s" ) . $file_name;

					}

					if (move_uploaded_file ( $_FILES [$k] ['tmp_name'], $save_path . $file_name )) {

						$to_attach [] = $save_path . $file_name;

					}

				}

			}

		}

		$subject = CI::model('core')->optionsGetByKey ( 'mailform_subject' );

		$subject = $subject . ' ' . $_REQUEST ['subject'];

		$headers .= "Content-type: text/plain; charset=utf-8\r\n";

		$headers .= "Content-Transfer-Encoding: quoted-printable\r\n";

		$headers .= "MIME-Version: 1.0\r\n";

		if (strval ( $_REQUEST ['email'] ) == '') {

			$_REQUEST ['email'] = 'unknown@ooyes.net';

			exit ();

		}

		$headers .= "From: {$_REQUEST['email']}" . "\r\n" . #    "Reply-To: webmaster@example.com" . "\r\n" .


		"Reply-To: {$_REQUEST['email']}" . "\r\n" . 'X-Mailer: Microweber (www.microweber.com) - PHP/' . phpversion ();

		$autoreply_msg = array ();
		foreach ( $_POST as $k => $v ) {

			if (stristr ( $k, 'autoreply' ) == false) {

				$what = str_replace ( '_', ' ', $k );

				$what = str_replace ( '-', ' ', $what );

				$message = $message . $what . ': ' . $v . "\r\n";
			} else {
				$auto_rep_temp = CI::model('core')->optionsGetByKeyAsArray ( 'mailform_' . trim ( $v ) );
				$autoreply_msg ['subject'] = $auto_rep_temp ['option_value'];
				$autoreply_msg ['message'] = $auto_rep_temp ['option_value2'];
				//var_dump($autoreply_msg);


			}
		}

		if (! empty ( $autoreply_msg )) {
			if (trim ( $autoreply_msg ['message'] ) != '') {
				$autoreply_msg_headers = false;

				$autoreply_msg_subject = $autoreply_msg ['subject'];
				$autoreply_msg_headers .= "Content-type: text/plain; charset=utf-8\r\n";
				$autoreply_msg_headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
				$autoreply_msg_headers .= "MIME-Version: 1.0\r\n";
				$sendto = CI::model('core')->optionsGetByKey ( 'mailform_to' );
				$autoreply_msg_headers .= "From: $sendto" . "\r\n" . #    "Reply-To: webmaster@example.com" . "\r\n" .
$to1 = $_REQUEST ['email'];

				mail ( $to1, $autoreply_msg_subject, $autoreply_msg ['message'], $autoreply_msg_headers );

			}
		}

		$message = $message . "\r\n";

		//$message = $message . 'City: ' . $city_name . "\r\n";


		$message = $message . 'IP: ' . $_SERVER ["REMOTE_ADDR"] . "\r\n";

		$message = $message . 'Date-Time: ' . date ( "Y-m-d H:i:s" ) . "\r\n";

		$message = $message . 'REF: ' . $_SERVER ["HTTP_REFERER"] . "\r\n";

		// $sendto = 'alexander.raikov@gmail.com';


		//$sendto = $the_city ['email'];
		//var_dump ( $to_attach );


		$send_me_the_email = true;

		$only_valiudation = false;

		if ($_POST) {

			if ($only_valiudation == false) {

				$sendto = CI::model('core')->optionsGetByKey ( 'mailform_to' );

				//				$sendto = 'boksiora@gmail.com';
				//				$sendto = 'stoil@ooyes.net';


				$sendto = explode ( ',', $sendto );

				if (! empty ( $sendto )) {

					foreach ( $sendto as $to ) {

						$to = trim ( $to );

						mail ( $to, $subject, $message, $headers );

					//UTF8_mail($_REQUEST['email'],$to,$subject,$message,$cc="",$bcc="");


					}

					exit ( '1' );

				} else {

					exit ( 'Nowhere to send (But how is that possible?), please define option with the key mailform_to from the admin panel and put your email there!' );

				}

			}

		}

	//$message.="\r\nMessage:" . $_REQUEST['message'];


	}

	function sitemaps() {

		ob_clean ();

		header ( "Content-Type: text/xml; charset=UTF-8" );

		//header ( "Content-Type: application/rss+xml" );


		global $cms_db_tables;

		$table = $cms_db_tables ['table_content'];

		$sitemaps_count = false;

		$q = "select count(*) as qty from $table  ";

		$q = CI::model('core')->dbQuery ( $q );

		$q = $q [0] ['qty'];

		$q = intval ( $q );

		$maps_count = ceil ( $q / 200 );

		$this->template ['maps_count'] = $maps_count;

		$map_id = $this->uri->segment ( 3 );

		$map_id = intval ( $map_id );

		if ($map_id == 0) {

			$this->template ['the_index'] = true;

			$this->load->vars ( $this->template );

			$upperLimit = $maps_count;

			$lowerLimit = 1;

			$updates = array ();

			$sitemaps_urls = array ();

			while ( $lowerLimit <= $upperLimit ) {

				$some = (($upperLimit - $lowerLimit) * 200) + 0;

				$some2 = (($upperLimit - $lowerLimit) * 200) + 1;

				$q = "select updated_on from $table  order by updated_on DESC limit 0,1 ";

				//var_dump($q);


				$q = CI::model('core')->dbQuery ( $q );

				$q = $q [0] ['updated_on'];

				$updates [] = $q;

				$sitemaps_urls [] = urlencode ( site_url ( 'main/sitemaps/' . $lowerLimit ) );

				$lowerLimit ++;

			}

			$this->template ['updates'] = $updates;

			$this->load->vars ( $this->template );

			foreach ( $sitemaps_urls as $item1 ) {

				$ch = curl_init ( "http://www.google.com/webmasters/tools/ping?sitemap=" . $item1 );

				curl_setopt ( $ch, CURLOPT_HEADER, 0 );

				curl_setopt ( $ch, CURLOPT_POST, 1 );

				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );

				$output = curl_exec ( $ch );

				curl_close ( $ch );

				//echo $output;


				$ch = curl_init ( "http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=" . $item1 );

				curl_setopt ( $ch, CURLOPT_HEADER, 0 );

				curl_setopt ( $ch, CURLOPT_POST, 1 );

				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );

				$output = curl_exec ( $ch );

				curl_close ( $ch );

			}

		} else {

			$some = (200) * ($map_id - 1);

			$some2 = (200) * ($map_id + 0);

			$q = "select id, updated_on from $table  order by updated_on DESC limit $some,$some2 ";

			$q = CI::model('core')->dbQuery ( $q );

			$updates = $q;

			$this->template ['updates'] = $updates;

			$this->load->vars ( $this->template );

		}

		//www.google.com/webmasters/tools/ping?sitemap=http%3A%2F%2Fwww.yoursite.com%2Fsitemap.gz


		$this->template ['posts'] = $posts;

		$this->load->vars ( $this->template );

		$layout = CI::view ( 'sitemap.php', true, true );

		CI::library('output')->set_output ( $layout );

	}

}






/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */