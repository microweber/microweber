<?php

if (!defined("MW_DB_TABLE_USERS")) {
	define('MW_DB_TABLE_USERS', MW_TABLE_PREFIX . 'users');
}

action_hook('mw_db_init_users', 'mw_db_init_users_table');

function mw_db_init_users_table() {
	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, 'db');

	if (($cache_content) != false) {

		return $cache_content;
	}

	$table_name = MW_DB_TABLE_USERS;

	$fields_to_add = array();

	$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('expires_on', 'datetime default NULL');
	$fields_to_add[] = array('last_login', 'datetime default NULL');

	$fields_to_add[] = array('created_by', 'int(11) default NULL');

	$fields_to_add[] = array('edited_by', 'int(11) default NULL');

	$fields_to_add[] = array('username', 'TEXT default NULL');

	$fields_to_add[] = array('password', 'TEXT default NULL');
	$fields_to_add[] = array('email', 'TEXT default NULL');

	$fields_to_add[] = array('is_active', "char(1) default 'n'");
	$fields_to_add[] = array('is_admin', "char(1) default 'n'");
	$fields_to_add[] = array('is_verified', "char(1) default 'n'");
	$fields_to_add[] = array('is_public', "char(1) default 'y'");

	$fields_to_add[] = array('first_name', 'TEXT default NULL');
	$fields_to_add[] = array('last_name', 'TEXT default NULL');

	$fields_to_add[] = array('parent_id', 'int(11) default NULL');

	$fields_to_add[] = array('user_information', 'TEXT default NULL');
	$fields_to_add[] = array('subscr_id', 'TEXT default NULL');
	$fields_to_add[] = array('role', 'TEXT default NULL');
	$fields_to_add[] = array('medium', 'TEXT default NULL');

	set_db_table($table_name, $fields_to_add);

	db_add_table_index('username', $table_name, array('username(255)'));
	db_add_table_index('email', $table_name, array('email(255)'));

	if (MW_IS_INSTALLED != true) {

		if (isset($_POST['admin_username']) and isset($_POST['admin_password'])) {

			$new_admin = array();
			$new_admin['username'] = $_POST['admin_username'];
			$new_admin['password'] = $_POST['admin_password'];
			$new_admin['is_active'] = 'y';
			$new_admin['is_admin'] = 'y';
			mw_var('FORCE_SAVE', MW_TABLE_PREFIX . 'users');
			save_user($new_admin);

		}

	}

	cache_store_data(true, $function_cache_id, $cache_group = 'db');
	// $fields = (array_change_key_case ( $fields, CASE_LOWER ));
	return true;

	//print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
}

//api_expose('register_user');
api_expose('register_user');

function register_user($params) {

	$user = isset($params['username']) ? $params['username'] : false;
	$pass = isset($params['password']) ? $params['password'] : false;
	$email = isset($params['email']) ? $params['email'] : false;

	if (!isset($params['captcha'])) {
		return array('error' => 'Please enter the captcha answer!');
	} else {
		$cap = session_get('captcha');
		if ($cap == false) {
			return array('error' => 'You must load a captcha first!');
		}
		if ($params['captcha'] != $cap) {
			return array('error' => 'Invalid captcha answer!');
		}
	}
	if (!isset($params['password'])) {
		return array('error' => 'Please set password!');
	} else {
		if ($params['password'] == '') {
			return array('error' => 'Please set password!');
		}
	}

	if ($email != false) {

		$data = array();
		$data['email'] = $email;
		$data['password'] = $pass;
		// $data ['is_active'] = 'y';
		$data = get_users($data);
		if (empty($data)) {

			$data = array();
			$data['username'] = $email;
			$data['password'] = $pass;
			// $data ['is_active'] = 'y';
			$data = get_users($data);
		}

		if (empty($data)) {
			$data = array();
			$data['username'] = $email;
			$data['password'] = $pass;
			$data['is_active'] = 'n';

			$table = MW_TABLE_PREFIX . 'users';

			$q = " INSERT INTO  $table set email='$email',  password='$pass',   is_active='n' ";
			$next = db_last_id($table);
			$next = intval($next) + 1;
			$q = "INSERT INTO $table (id,email, password, is_active)
VALUES ($next, '$email', '$pass', 'n')";
			db_q($q);
			cache_clean_group('users' . DIRECTORY_SEPARATOR . 'global');
			//$data = save_user($data);
			session_del('captcha');

			return array($next);
		} else {
			return array('error' => 'This user already exists!');
		}
	}
}

api_expose('save_user');

function save_user($params) {

	if (isset($params['id'])) {
		//error('COMLETE ME!!!! ');

		$adm = is_admin();
		if ($adm == false) {
			error('Error: not logged in as admin.');
		}
	} else {
		if (MW_IS_INSTALLED == true) {
			error('COMLETE ME!!!! ');
		}
	}

	$data_to_save = $params;

	$table = MW_TABLE_PREFIX . 'users';
	$save = save_data($table, $data_to_save);
	$id = $save;
	cache_clean_group('users' . DIRECTORY_SEPARATOR . 'global');
	cache_clean_group('users' . DIRECTORY_SEPARATOR . '0');
	cache_clean_group('users' . DIRECTORY_SEPARATOR . $id);
	return $id;
}

api_expose('captcha');

function captcha_vector($palette, $startx, $starty, $angle, $length, $colour) {
	$angle = deg2rad($angle);
	$endx = $startx + cos($angle) * $length;
	$endy = $starty - sin($angle) * $length;
	return (imageline($palette, $startx, $starty, $endx, $endy, $colour));
}

function captcha() {
	$roit1 = rand(1, 6);
	$font = INCLUDES_DIR . DS . 'admin' . DS . 'catcha_fonts' . DS . 'font' . $roit1 . '.ttf';
	$font = normalize_path($font, 0);

	header("Content-type: image/png");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	$text1 = mt_rand(100, 4500);
	$text2 = mt_rand(2, 9);
	$roit = mt_rand(1, 5);
	$text = "$text1";
	$answ = $text1;
	$x = 100;
	$y = 20;
	$image = @imagecreate($x, 20) or die("Unable to render a CAPTCHA picture!");

	$tcol1z = rand(1, 150);
	$ttcol1z1 = rand(0, 150);
	$tcol1z11 = rand(0, 150);

	$bgcolor = imagecolorallocate($image, 255, 255, 255);
	// $black = imagecolorallocate($image, $tcol1z, $ttcol1z1, $tcol1z11);
	$black = imagecolorallocate($image, 0, 0, 0);
	session_set('captcha', $answ);

	$col1z = rand(200, 242);
	$col1z1 = rand(150, 242);
	$col1z11 = rand(150, 242);
	$color1 = imagecolorallocate($image, $col1z, $col1z1, $tcol1z11);
	$color2 = imagecolorallocate($image, $tcol1z - 1, $ttcol1z1 - 1, $tcol1z11 - 2);
	// imagefill($image, 0, 0, $color1);
	for ($i = 0; $i < $x; $i++) {
		for ($j = 0; $j < $y; $j++) {
			if (mt_rand(0, 20) == 20) {

				//  $coords = array(mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), 5, 6);

				$y21 = mt_rand(5, 20);
				captcha_vector($image, $x - mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 180), 200, $bgcolor);
				//   imagesetpixel($image, $i, $j, $color2);
			}
		}
	}
	$x1 = mt_rand(15, 30);
	$y1 = mt_rand(15, 20);
	$tsize = rand(11, 13);
	imagettftext($image, $tsize, $roit, $x1, $y1, $black, $font, $text);

	$y21 = mt_rand(5, 20);
	captcha_vector($image, $x, $y21 / 2, 180, 200, $bgcolor);

	$y21 = mt_rand(5, 20);
	captcha_vector($image, $x, $y21 / 2, $col1z11, 200, $bgcolor);

	$y21 = mt_rand(5, 20);
	captcha_vector($image, $x / 3, $y21 / 3, $col1z11, 200, $bgcolor);

	//   imagestring($image, 5, 2, 2, $text, $black);

	$emboss = array( array(2, 0, 0), array(0, -1, 0), array(0, 0, -1));
	$embize = mt_rand(1, 4);
	// imageconvolution($image, $emboss, $embize, 255);
	//   imagefilter($image, IMG_FILTER_SMOOTH, 50);
	imagepng($image);
	imagecolordeallocate($image, $bgcolor);
	imagecolordeallocate($image, $black);

	imagedestroy($image);
}

function user_login($params) {
	$params2 = array();

	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
	}

	if (isset($params) and !empty($params)) {

		$user = isset($params['username']) ? $params['username'] : false;
		$pass = isset($params['password']) ? $params['password'] : false;
		$email = isset($params['email']) ? $params['email'] : false;

		$data = array();
		$data['username'] = $user;
		$data['password'] = $pass;

		// $data ['is_active'] = 'y';

		$data = get_users($data);

		if (isset($data[0])) {
			$data = $data[0];
		} else {
			if (trim($email) != '') {
				$data = array();
				$data['email'] = $email;
				$data['password'] = $pass;
				//$data['debug'] = 1;
				$data = get_users($data);
				if (isset($data[0])) {
					$data = $data[0];
				} else {

				}
			}

			// return false;
		}

		if (!isarr($data)) {
			if (trim($user) != '') {
				$data = array();
				$data['email'] = $user;
				$data['password'] = $pass;
				// $data ['debug'] = 1;

				$data = get_users($data);

				if (isset($data[0])) {
					$data = $data[0];
				}
			}
		}
		if (!isarr($data)) {

			return false;
		} else {

			$user_session['is_logged'] = 'yes';
			$user_session['user_id'] = $data['id'];

			if (!defined('USER_ID')) {
				define("USER_ID", $data['id']);
			}

			session_set('user_session', $user_session);
			$user_session = session_get('user_session');
			if (isset($data["is_admin"]) and $data["is_admin"] == 'y') {
				if (isset($params['where_to']) and $params['where_to'] == 'live_edit') {

					$p = get_page();
					if (!empty($p)) {
						$link = page_link($p['id']);
						$link = $link . '/editmode:y';
						safe_redirect($link);
					}
				}
			}

			$aj = isAjax();

			if ($aj == false) {
				if (isset($_SERVER["HTTP_REFERER"])) {
					safe_redirect($_SERVER["HTTP_REFERER"]);
					exit();
				}
			}

			return $user_session;
		}
	}

	return false;
}

api_expose('logout');

function logout() {

	if (!defined('USER_ID')) {
		define("USER_ID", false);
	}

	// static $uid;
	$aj = isAjax();
	session_end();

	if (isset($_COOKIE['editmode'])) {
		setcookie('editmode');
	}

	if ($aj == false) {
		if (isset($_SERVER["HTTP_REFERER"])) {
			safe_redirect($_SERVER["HTTP_REFERER"]);
		}
	}
}

function user_id() {
	// static $uid;
	if (defined('USER_ID')) {
		// print USER_ID;
		return USER_ID;
	} else {

		$user_session = session_get('user_session');

		$res = false;
		if (isset($user_session['user_id'])) {
			$res = $user_session['user_id'];
		}

		if ($res != false) {
			// $res = $sess->get ( 'user_id' );
			define("USER_ID", $res);
		}
		return $res;
	}
}

function has_access($function_name) {

	$is_a = is_admin();

	if ($is_a == true) {
		return true;
	} else {
		return false;
	}
}

function is_admin() {
	static $is = 0;
	if (MW_IS_INSTALLED == false) {
		return true;
	}
	if ($is != 0 or defined('USER_IS_ADMIN')) {
		// var_dump( $is);
		return $is;
	} else {
		$usr = user_id();
		if ($usr == false) {
			return false;
		}
		$usr = get_user($usr);

		if (isset($usr['is_admin']) and $usr['is_admin'] == 'y') {
			define("USER_IS_ADMIN", true);
			define("IS_ADMIN", true);
		} else {
			define("USER_IS_ADMIN", false);
			define("IS_ADMIN", false);
		}
		$is = USER_IS_ADMIN;
		// var_dump( $is);
		// var_dump( $is);
		// var_dump( USER_IS_ADMIN.USER_IS_ADMIN.USER_IS_ADMIN);
		return USER_IS_ADMIN;
	}
}

/**
 * user_name
 *
 * gets the user's FULL name
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $user_id -
 *        	the is of the user. If false it will use the curent user (you)
 * @param string $mode
 *        	= 'full' //prints full name (first +last)
 *
 *        	$mode = 'first' //prints first name
 *        	$mode = 'last' //prints last name
 *        	$mode = 'username' //prints username
 *
 */
function user_name($user_id = false, $mode = 'full') {
	if ($mode != 'username') {
		if ($user_id == user_id()) {
			// return 'You';
		}
	}
	if ($user_id == false) {
		$user_id = user_id();
	}

	$name = nice_user_name($user_id, $mode);
	return $name;
}

/**
 * get_users
 *
 * get_users
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $params =
 *        	array();
 * @return array array of users;
 */
function get_users($params = array()) {
	$params2 = array();

	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
	}

	$table = MW_TABLE_PREFIX . 'users';

	$data = string_clean($params);
	$orig_data = $data;

	if (isset($data['ids']) and is_array($data['ids'])) {
		if (!empty($data['ids'])) {
			$ids = $data['ids'];
		}
	}

	$data['search_by_keyword_in_fields'] = array('first_name', 'last_name', 'username', 'email');
	// $data ['debug'] = 1;
	$cache_group = 'users/global';
	if (isset($data['id']) and intval($data['id']) != 0) {
		$cache_group = 'users/' . $data['id'];
	} else {

	}
	$cache_group = 'users/global';
	if (isset($limit) and $limit != false) {
		$data['limit'] = $limit;
	}

	if (isset($count_only) and $count_only != false) {
		$data['get_count'] = $count_only;
	}

	if (isset($data['only_those_fields']) and $data['only_those_fields']) {
		$only_those_fields = $data['only_those_fields'];
	}

	if (isset($data['count']) and $data['count']) {
		$count_only = $data['count'];
	}

	// $data ['no_cache'] = 1;

	if (isset($data['username']) and $data['username'] == null) {
		unset($data['username']);
	}
	// p ( $data );
	// .//p($data);
	// $get = $this->core_model->fetchDbData ( $table, $data );

	$function_cache_id = false;

	$args = func_get_args();
	$i = 0;
	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);

		$i++;
	}

	// $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
	//  $data ['table'] = $table;
	//  $data ['cache_group'] = $cache_group;
	//  $get = get($data);

	$get = db_get($table, $criteria = $data, $cache_group);
	// $get = db_get($table, $criteria = $data, $cache_group);
	// var_dump($get, $function_cache_id, $cache_group);
	//  cache_store_data($get, $function_cache_id, $cache_group);

	return $get;
}

/**
 * get_user
 *
 * get_user get the user info from the DB
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $id =
 *        	the id of the user;
 * @return array
 */
function get_user($id = false) {
	if ($id == false) {
		$id = user_id();
	}

	$res = get_user_by_id($id);

	if (empty($res)) {

		$res = get_user_by_username($id);
	}

	return $res;
}

/**
 * Generic function to get the user by id.
 * Uses the getUsers function to get the data
 *
 * @param
 *        	int id
 * @return array
 *
 */
function get_user_by_id($id) {
	$id = intval($id);
	if ($id == 0) {
		return false;
	}

	$data = array();
	$data['id'] = $id;
	$data['limit'] = 1;
	$data = get_users($data);
	if (isset($data[0])) {
		$data = $data[0];
	}
	return $data;
}

function get_user_by_username($username) {
	$data = array();
	$data['username'] = $username;
	$data['limit'] = 1;
	$data = get_users($data);
	if (isset($data[0])) {
		$data = $data[0];
	}
	return $data;
}

/**
 * Function to get user printable name by given ID
 *
 * @param
 *        	$id
 * @param
 *        	$mode
 * @return string
 * @usage Delete relation:
 *          $this->users_model->getPrintableName(10, 'full');
 *
 */
function nice_user_name($id, $mode = 'full') {
	$user = get_user_by_id($id);
	$user_data = $user;
	if (empty($user)) {
		return false;
	}

	switch ($mode) {
		case 'first' :
		case 'fist' :
			// because of a common typo :)
			$user_data['first_name'] ? $name = $user_data['first_name'] : $name = $user_data['username'];
			$name = ucwords($name);
			return $name;
			break;

		case 'last' :
			$user_data['last_name'] ? $name = $user_data['last_name'] : $name = $user_data['last_name'];
			$name = ucwords($name);
			return $name;
			break;

		case 'username' :
			$name = $user_data['username'];
			return $name;
			break;

		case 'full' :
		default :
			$name = $user_data['first_name'] . ' ' . $user_data['last_name'];

			if (trim($name) == '') {
				$name = $user_data['username'];
			}

			$name = ucwords($name);
			return $name;

			break;
	}
	exit();
}

/**
 * get_new_users
 *
 * get_new_users
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $period =
 *        	7 days;
 * @return array $ids - array of user ids;
 */
function get_new_users($period = '7 days', $limit = 20) {

	// $CI = get_instance ();
	get_instance() -> load -> model('Users_model', 'users_model');
	$data = array();
	$data['created_on'] = '[mt]-' . $period;
	$data['fields'] = array('id');
	$limit = array('0', $limit);
	// $data['debug']= true;
	// $data['no_cache']= true;
	$data =          get_instance() -> users_model -> getUsers($data, $limit, $count_only = false);
	$res = array();
	if (!empty($data)) {
		foreach ($data as $item) {
			$res[] = $item['id'];
		}
	}
	return $res;
}

function user_id_from_url() {
	if (url_param('username')) {
		$usr = url_param('username');
		// $CI = get_instance ();
		get_instance() -> load -> model('Users_model', 'users_model');
		$res =          get_instance() -> users_model -> getIdByUsername($username = $usr);
		return $res;
	}

	if (url_param('user_id')) {
		$usr = url_param('user_id');
		return $usr;
	}
	return user_id();
}

/**
 * user_thumbnail
 *
 * get the user_thumbnail of the user
 *
 * @access public
 * @category general
 * @author Microweber
 * @link http://microweber.com
 * @param $params =
 *        	array();
 *        	$params['id'] = 15; //the user id
 *        	$params['size'] = 200; //the thumbnail size
 * @return string - The thumbnail link.
 * @usage Use
 *          user_thumbnail
 *
 *          get the user_thumbnail of the user
 *
 * @access public
 * @category general
 * @author Microweber
 * @link http://microweber.com
 * @param $params =
 *        	array();
 *        	$params['id'] = 15; //the user id
 *        	$params['size'] = 200; //the thumbnail size
 * @return string - The thumbnail link.
 * @usage Use print post_thumbnail($post['id']);
 */
function user_picture($params) {
	return user_thumbnail($params);
}

function user_thumbnail($params) {
	$params2 = array();

	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
	}

	//
	// $CI = get_instance ();

	if (!$params['size']) {
		$params['size'] = 200;
	}

	// $pic = get_picture($params ['id'], $for = 'user');
	// $media = get_instance()->core_model->mediaGetThumbnailForMediaId (
	// $pic['id'],
	// $params ['size'], $size_height );
	// p($media);

	$thumb =          get_instance() -> core_model -> mediaGetThumbnailForItem($to_table = 'table_users', $to_table_id = $params['id'], $params['size'], 'DESC');

	return $thumb;
}

function users_count() {
	$options = array();
	$options['get_count'] = true;
	// $options ['debug'] = true;
	$options['count'] = true;
	// $options ['no_cache'] = true;
	$options['cache_group'] = 'users/global/';

	$data = get_users($options);

	return $data;
}

function cf_get_user($user_id, $field_name) {
	$fields = get_custom_fields_for_user($user_id);
	if (empty($fields)) {
		return false;
	}

	foreach ($fields as $field) {
		if (trim(strtolower($field_name)) == trim(strtolower($field['custom_field_name']))) {

			if ($field['custom_field_value']) {
				return $field['custom_field_value'];
			} else {

				if ($field['custom_field_values']) {
					return $field['custom_field_values'];
				}
			}

			// p ( $field );
		}
	}
}

function get_custom_fields_for_user($user_id, $field_name = false) {
	// p($content_id);
	$more = false;
	$more =          get_instance() -> core_model -> getCustomFields('table_users', $user_id, true, $field_name);
	return $more;
}

function friends_count($user_id = false) {

	// $CI = get_instance ();
	if ($user_id == false) {
		$user_id = user_id();
	}

	$query_options = array();

	$query_options['get_count'] = true;
	$query_options['debug'] = false;
	$query_options['group_by'] = false;
	get_instance() -> load -> model('Users_model', 'users_model');
	$users =          get_instance() -> users_model -> realtionsGetFollowedIdsForUser($aUserId = $user_id, $special = false, $query_options);
	return intval($users);
}
