<?php

if (!defined("MODULE_DB_USERS_ONLINE")) {
	define('MODULE_DB_USERS_ONLINE', MW_TABLE_PREFIX . 'stats_users_online');
}

event_bind('mw_admin_dashboard_main', 'mw_print_stats_on_dashboard');

function mw_print_stats_on_dashboard() {
	$active = mw('url')->param('view');
	$cls = '';
	if ($active == 'shop') {
		//   $cls = ' class="active" ';
	}
	print '<microweber module="site_stats" view="admin" />';
}

function mw_install_stats_module($config = false) {
	return true;
	if (is_admin() == false) {
		return false;
	}
	mw('cache')->delete('stats');

	$this_dir = dirname(__FILE__);

	$sql = $this_dir . DS . 'install.sql';
	$cfg = $this_dir . DS . 'config.php';

	$is_installed = db_table_exist(MODULE_DB_USERS_ONLINE);
	//d($is_installed);
	if ($is_installed == false) {
		$install = import_sql_from_file($sql);
		//   mw('cache')->delete('db');

		return true;
	} elseif (is_array($is_installed) and !empty($is_installed)) {

	} else {

		return false;
	}
	//d($install);
}

function mw_uninstall_stats_module() {
	if (is_admin() == false) {
		return false;
	}

	$table = MODULE_DB_USERS_ONLINE;
	$q = "DROP TABLE IF EXISTS {$table}; ";
	//d($q);

	mw('db')->q($q);
	mw('cache')->delete('stats');
	//  mw('cache')->delete('db');
}

//document_ready('stats_append_image');

event_bind('frontend', 'stats_append_image');

function stats_append_image($layout = false) {

	//if (!defined('IN_ADMIN')) {
	//<table(?=[^>]*class="details")[^>]*>
	//$selector = '<body>';
	if (!isset($_REQUEST['isolate_content_field'])) {
		stats_insert();
		//$selector = '/<\/body>/si';
		////$rand = date("Y-m-d");
		//$layout = modify_html($layout, $selector, '<img src="' . mw_site_url('api/stats_image?rand=' . $rand) . '" height="1" class="semi_hidden statts_img" />', 'prepend');
	}
	//}
	//$layout = mw_dom($layout, 'ul.mw-quick-links:last', '<li><a href="#"><span class="ico ihelp"></span><span>I am dynamic</span></a></li>');

	//   $layout = modify_html($layout, $selector = '.editor_wrapper', 'append', 'ivan');
	//$layout = modify_html2($layout, $selector = '<div class="editor_wrapper">', '');
	//return $layout;
}

api_expose('stats_image');

function stats_image() {
	stats_insert();

	$f = dirname(__FILE__);
	$f = $f . DS . '1px.png';
	$name = $f;
	$fp = fopen($name, 'rb');

	// send the right headers
	header("Content-Type: image/png");
	header("Content-Length: " . filesize($name));

	// dump the picture and stop the script
	fpassthru($fp);
	exit ;
}

function stats_insert() {

	$function_cache_id = false;
	$uip = $_SERVER['REMOTE_ADDR'];
	$function_cache_id = $function_cache_id . $uip . MW_USER_IP;

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);
$few_mins_ago_visit_date = date("Y-m-d H:i:s");
	//$cache_content = mw('cache')->get($function_cache_id, $cache_group = 'module_stats_users_online');
	//if (($cache_content) == '--false--') {
	//return false;
	//	}
	$cookie_name = 'mw-stats' . crc32($function_cache_id);
	$cookie_name_time = 'mw-time' . crc32($function_cache_id);

	$vc1 = 1;
	 if (isset($_SESSION[$cookie_name])) {
		$vc1 = intval($_SESSION[$cookie_name]) + 1;
		$_SESSION[$cookie_name] = $vc1;
		//	setcookie($cookie_name, $vc1);
		//  return true;
	} elseif (!isset($_SESSION[$cookie_name])) {
		//setcookie($cookie_name, $vc1);
		$_SESSION[$cookie_name] = $vc1;
		// return true;
	} 
	
	 
	if (!isset($_COOKIE[$cookie_name_time])) {
	
		if(!headers_sent()){
				setcookie($cookie_name_time, $few_mins_ago_visit_date, time() + 90);
		}
		$data = array();
		$data['visit_date'] = date("Y-m-d");
		$data['visit_time'] = date("H:i:s");
		//   $data['debug'] = date("H:i:s");

		$table = MODULE_DB_USERS_ONLINE;
		$check = mw('db')->get("no_cache=1&table={$table}&user_ip={$uip}&one=1&limit=1&visit_date=" . $data['visit_date']);
		if ($check != false and is_array($check) and !empty($check) and isset($check['id'])) {
			$data['id'] = $check['id'];
			$vc = 0;
			if (isset($check['view_count'])) {
				//	d($check);
				$vc = ($check['view_count']);
			}

			$vc1 = 0;
			if (isset($_SESSION[$cookie_name])) {
				$vc1 = intval($_SESSION[$cookie_name]);
			}
			$vc = $vc + $vc1;
			$data['view_count'] = $vc;
		}
		//d($data);
		if (isset($_SERVER['HTTP_REFERER'])) {
			$lp = $_SERVER['HTTP_REFERER'];
		} else {
			$lp = $_SERVER['PHP_SELF'];
		}
		$data['last_page'] = $lp;
		$data['skip_cache'] = 1;
		//	 $data['debug'] = $lp;

		mw_var('FORCE_SAVE', $table);
		mw_var('apc_no_clear', 1);
		$save = mw('db')->save($table, $data);
 	$_SESSION[$cookie_name] = 0;
		 
		mw_var('apc_no_clear', 0);
		//	setcookie($cookie_name, 1);

	} else {

	}
	return true;

}


function stats_insert_cookie_based() {

	$function_cache_id = false;
	$uip = $_SERVER['REMOTE_ADDR'];
	$function_cache_id = $function_cache_id . $uip . MW_USER_IP;

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	//$cache_content = mw('cache')->get($function_cache_id, $cache_group = 'module_stats_users_online');
	//if (($cache_content) == '--false--') {
	//return false;
	//	}
	$cookie_name = 'mw-stats' . crc32($function_cache_id);
	$cookie_name_time = 'mw-time' . crc32($function_cache_id);

	$vc1 = 1;
	/*if (isset($_SESSION[$cookie_name])) {
		$vc1 = intval($_SESSION[$cookie_name]) + 1;
		$_SESSION[$cookie_name] = $vc1;
		//	setcookie($cookie_name, $vc1);
		//  return true;
	} elseif (!isset($_SESSION[$cookie_name])) {
		//setcookie($cookie_name, $vc1);
		$_SESSION[$cookie_name] = $vc1;
		// return true;
	}*/
	
		$few_mins_ago_visit_date = date("Y-m-d H:i:s");
	if (isset($_COOKIE[$cookie_name])) {
		$vc1 = intval($_COOKIE[$cookie_name]) + 1;
	//	$_SESSION[$cookie_name] = $vc1;
		 setcookie($cookie_name, $vc1, time() + 99);
		//  return true;
	} elseif (!isset($_COOKIE[$cookie_name])) {
		 setcookie($cookie_name, $vc1, time() + 99);
		//$_SESSION[$cookie_name] = $vc1;
		// return true;
	}

	if (!isset($_COOKIE[$cookie_name_time])) {
	

		setcookie($cookie_name_time, $few_mins_ago_visit_date, time() + 90);

		$data = array();
		$data['visit_date'] = date("Y-m-d", strtotime("now"));
		$data['visit_time'] = date("H:i:s", strtotime("now"));
		//   $data['debug'] = date("H:i:s");

		$table = MODULE_DB_USERS_ONLINE;
		$check = mw('db')->get("no_cache=1&table={$table}&user_ip={$uip}&one=1&limit=1&visit_date=" . $data['visit_date']);
		if ($check != false and is_array($check) and !empty($check) and isset($check['id'])) {
			$data['id'] = $check['id'];
			$vc = 0;
			if (isset($check['view_count'])) {
				//	d($check);
				$vc = ($check['view_count']);
			}

			$vc1 = 0;
			if (isset($_COOKIE[$cookie_name])) {
				$vc1 = intval($_COOKIE[$cookie_name]);
			}
			$vc = $vc + $vc1;
			$data['view_count'] = $vc;
		}
		//d($data);
		if (isset($_SERVER['HTTP_REFERER'])) {
			$lp = $_SERVER['HTTP_REFERER'];
		} else {
			$lp = $_SERVER['PHP_SELF'];
		}
		$data['last_page'] = $lp;
		$data['skip_cache'] = 1;
		//	 $data['debug'] = $lp;

		mw_var('FORCE_SAVE', $table);
		mw_var('apc_no_clear', 1);
		$save = mw('db')->save($table, $data);
	//	$_SESSION[$cookie_name] = 0;
		 setcookie($cookie_name,0, time() + 99);

		mw_var('apc_no_clear', 0);
		//	setcookie($cookie_name, 1);

	} else {

	}
	return true;

}

function get_visits($range = 'daily') {
	$table = MODULE_DB_USERS_ONLINE;
	$q = false;
	$results = false;
	switch ($range) {
		case 'daily' :
			$ago = date("Y-m-d", strtotime("-1 month"));
			$q = "SELECT COUNT(*) AS unique_visits, SUM(view_count) as total_visits, visit_date FROM $table where visit_date > '$ago' group by visit_date  ";
			$results = mw('db')->query($q);

			break;

		case 'weekly' :
			$ago = date("Y-m-d", strtotime("-1 year"));
			//

			$q = "SELECT COUNT(*) AS unique_visits, SUM(view_count) as total_visits,visit_date, DATE_FORMAT(visit_date, '%x %V') as weeks  FROM $table where visit_date > '$ago' group by weeks  ";

			// d($q);
			$results = mw('db')->query($q);

			break;

		case 'monthly' :
			$ago = date("Y-m-d", strtotime("-1 year"));
			//

			$q = "SELECT COUNT(*) AS unique_visits, SUM(view_count) as total_visits,visit_date, DATE_FORMAT(visit_date, '%x %m') as months  FROM $table where visit_date > '$ago' group by months  ";

			// d($q);
			$results = mw('db')->query($q);

			break;

		case 'last5' :
			 $q = "SELECT * FROM $table order by visit_date DESC, visit_time DESC limit 5  ";

			  
			$results = mw('db')->query($q);

			break;


		case 'requests_num' :
			$ago = date("H:i:s", strtotime("-1 minute"));
			$ago2 = date("Y-m-d", strtotime("now"));
			$total = 0;
			$q = "SELECT SUM(view_count) as total_visits FROM $table  where visit_date='$ago2' and visit_time>'$ago'   ";
			// d($q);
			$results = mw('db')->query($q);
			if(isset($results[0]) and isset($results[0]['total_visits'])){
				
			
			
				$mw_req_sec = mw('user')->session_get('stats_requests_num');
			
			
				
				$total = $results[0]['total_visits'];
				mw('user')->session_set('stats_requests_num',$total);
				
				$results = intval($total)-intval($mw_req_sec);
			}  else { 
				$results = false;
			}
		
			break;


		case 'users_online' :

			//$data['visit_time'] = date("H:i:s");
			$ago = date("H:i:s", strtotime("-5 minutes"));
			$ago2 = date("Y-m-d", strtotime("now"));
			$q = "SELECT COUNT(*) AS users_online FROM $table where visit_date='$ago2' and visit_time>'$ago'    ";
 
			$results = mw('db')->query($q);
			$results = intval($results[0]['users_online']);

			//	$q = 'SELECT COUNT(*) AS count FROM ' . $table . ' WHERE visit_date > '';
			//
			break;

		default :
			break;
	}

	if ($q == false or $results == false) {
		return false;
	}
	if (is_array($results)) {
		foreach ($results as $item) {

		}
	}

	return $results;
}
