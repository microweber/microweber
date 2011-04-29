<?php //require_once 'Zend/Db/Adapter/Mysqli.php';
//require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
require_once 'cyrlat/cyrlat.class.php';

//var_dump($db);
//exit;
//$cms_db_options = array (Zend_Db::AUTO_QUOTE_IDENTIFIERS => false );


//$cms_db = Zend_Db::factory ( 'Mysqli', array ('host' => DBHOSTNAME, 'username' => DBUSERNAME, 'password' => DBPASSWORD, 'dbname' => DBDATABASE, 'options' => $cms_db_options ) );


//$cms_db->setFetchMode ( Zend_Db::FETCH_ASSOC );


$cms_db_tables = array ();
$cms_db_tables ['table_cache'] = TABLE_PREFIX . 'cache';
$cms_db_tables ['table_content'] = TABLE_PREFIX . 'content';

$cms_db_tables ['table_taxonomy'] = TABLE_PREFIX . 'taxonomy';
$cms_db_tables ['table_taxonomy_items'] = TABLE_PREFIX . 'taxonomy_items';

$cms_db_tables ['table_menus'] = TABLE_PREFIX . 'menus';
$cms_db_tables ['table_options'] = TABLE_PREFIX . 'options';
$cms_db_tables ['table_media'] = TABLE_PREFIX . 'media';
$cms_db_tables ['table_geodata'] = TABLE_PREFIX . 'geodata';
$cms_db_tables ['table_comments'] = TABLE_PREFIX . 'comments';
$cms_db_tables ['table_votes'] = TABLE_PREFIX . 'votes';
$cms_db_tables ['table_users'] = TABLE_PREFIX . 'users';
$cms_db_tables ['table_messages'] = TABLE_PREFIX . 'messages';
$cms_db_tables ['table_users_log'] = TABLE_PREFIX . 'users_log';
$cms_db_tables ['table_users_statuses'] = TABLE_PREFIX . 'users_statuses';

$cms_db_tables ['table_stats_sites'] = TABLE_PREFIX . 'statssite';

$cms_db_tables ['table_users_notifications'] = TABLE_PREFIX . 'users_notifications';
$cms_db_tables ['table_users_statuses'] = TABLE_PREFIX . 'users_statuses';
$cms_db_tables ['table_followers'] = TABLE_PREFIX . 'followers';

$cms_db_tables ['table_sessions'] = TABLE_PREFIX . 'sessions';
$cms_db_tables ['table_custom_fields'] = TABLE_PREFIX . 'content_custom_fields';
$cms_db_tables ['table_custom_fields_config'] = TABLE_PREFIX . 'content_custom_fields_config';
$cms_db_tables ['table_cart'] = TABLE_PREFIX . 'cart';
$cms_db_tables ['table_cart_orders'] = TABLE_PREFIX . 'cart_orders';
$cms_db_tables ['table_cart_promo_codes'] = TABLE_PREFIX . 'cart_promo_codes';
$cms_db_tables ['table_countries'] = TABLE_PREFIX . 'countries';
$cms_db_tables ['table_cart_orders_shipping_cost'] = TABLE_PREFIX . 'cart_orders_shipping_cost';
$cms_db_tables ['table_cart_currency'] = TABLE_PREFIX . 'cart_currency';
$cms_db_tables ['table_reports'] = TABLE_PREFIX . 'reports';

//stats 
$cms_db_tables ['table_stats_site'] = 'piwik_site';
$cms_db_tables ['table_stats_access'] = 'piwik_access';
$cms_db_tables ['table_log_action'] = 'piwik_log_action';
$cms_db_tables ['table_log_link_visit_action'] = 'piwik_log_link_visit_action';

//use this array to exlude certain table interactions from the users log table
$users_log_exclude = array (); //not used if $users_log_include is not empty
$users_log_exclude [] = 'table_users_notifications';
$users_log_exclude [] = 'table_media';
$users_log_exclude [] = 'table_cart_orders_shipping_cost';
$users_log_exclude [] = 'table_cart_orders';
$users_log_exclude [] = 'table_taxonomy';
$users_log_exclude [] = 'table_sessions';
$users_log_exclude [] = 'table_messages';
$users_log_exclude [] = 'table_users_log'; // :) no loops pls
$users_log_exclude [] = 'table_countries';
$users_log_exclude [] = 'table_stats';
$users_log_exclude [] = 'table_options';
$users_log_exclude [] = 'table_reports';

//use this array to force certain table interactions from the users log table
$users_log_include = array ();
$users_log_include [] = 'table_comments';
$users_log_include [] = 'table_users';
$users_log_include [] = 'table_users_statuses';
$users_log_include [] = 'table_content';
$users_log_include [] = 'table_votes';
$users_log_include [] = 'table_followers';

$_GLOBALS ['cms_db_tables'] = $cms_db_tables;

//$_GLOBALS ['cms_db'] = $cms_db;.


$cms_db_tables_search_fields = array ('content_title', "content_body", "content_url" );
$_GLOBALS ['cms_db_tables_search_fields'] = $cms_db_tables_search_fields;

$_GLOBALS ['loaded_plugins'] = array ();

if (is_dir ( USERFILES ) == false) {
	
	@mkdir ( USERFILES );

}

if (is_dir ( TEMPLATEFILES ) == false) {
	
	@mkdir ( TEMPLATEFILES );

}

if (is_dir ( MEDIAFILES ) == false) {
	
	@mkdir ( MEDIAFILES );

}

function word_cleanup($str) {
	$pattern = "/<(\w+)>(\s|&nbsp;)*<\/\1>/";
	$str = preg_replace ( $pattern, '', $str );
	return mb_convert_encoding ( $str, 'HTML-ENTITIES', 'UTF-8' );
}
/**
 * Recursive glob()
 */

/**
 * @param int $pattern
 * the pattern passed to glob()
 * @param int $flags
 * the flags passed to glob()
 * @param string $path
 * the path to scan
 * @return mixed
 * an array of files in the given path matching the pattern.
 */

function rglob($pattern = '*', $flags = 0, $path = '') {
	$paths = glob ( $path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT );
	$files = glob ( $path . $pattern, $flags );
	foreach ( $paths as $path ) {
		$files = array_merge ( $files, rglob ( $pattern, $flags, $path ) );
	}
	return $files;
}
function add_date($givendate, $day = 0, $mth = 0, $yr = 0) {
	$cd = strtotime ( $givendate );
	$newdate = date ( 'Y-m-d h:i:s', mktime ( date ( 'h', $cd ), date ( 'i', $cd ), date ( 's', $cd ), date ( 'm', $cd ) + $mth, date ( 'd', $cd ) + $day, date ( 'Y', $cd ) + $yr ) );
	return $newdate;
}

if (! function_exists ( 'getCurentURL' )) {
	
	function getCurentURL() {
		
		$pageURL = 'http';
		
		if ($_SERVER ["HTTPS"] == "on") {
			
			$pageURL .= "s";
		
		}
		
		$pageURL .= "://";
		
		if ($_SERVER ["SERVER_PORT"] != "80") {
			
			$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
		
		} else {
			
			$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
		
		}
		
		return $pageURL;
	
	}

}

if (! function_exists ( 'array_extend' )) {
	function array_extend($a, $b) {
		foreach ( $b as $k => $v ) {
			if (is_array ( $v )) {
				if (! isset ( $a [$k] )) {
					$a [$k] = $v;
				} else {
					$a [$k] = array_extend ( $a [$k], $v );
				}
			} else {
				$a [$k] = $v;
			}
		}
		return $a;
	}
}

function array_push_array($arr) {
	$args = func_get_args ();
	array_shift ( $args );
	
	if (! is_array ( $arr )) {
		trigger_error ( sprintf ( "%s: Cannot perform push on something that isn't an array!", __FUNCTION__ ), E_USER_WARNING );
		return false;
	}
	
	foreach ( $args as $v ) {
		if (is_array ( $v )) {
			if (count ( $v ) > 0) {
				array_unshift ( $v, $arr );
				call_user_func_array ( 'array_push', $v );
			}
		} else {
			$arr [] = $v;
		}
	}
	return count ( $arr );
}

function domNodeContent($n, $outer = false) {
	$d = new DOMDocument ( '1.0' );
	$b = $d->importNode ( $n->cloneNode ( true ), true );
	$d->appendChild ( $b );
	$h = $d->saveHTML ();
	// remove outter tags
	if (! $outer)
		$h = substr ( $h, strpos ( $h, '>' ) + 1, - (strlen ( $n->nodeName ) + 4) );
	return $h;
}

function array_values_deep($array) {
	$temp = array ();
	foreach ( $array as $key => $value ) {
		if (is_numeric ( $key )) {
			$temp [] = is_array ( $value ) ? array_values_deep ( $value ) : $value;
		} else {
			$temp [$key] = is_array ( $value ) ? array_values_deep ( $value ) : $value;
		}
	}
	return $temp;
}
function array_rpush($arr, $item) {
	$arr = array_pad ( $arr, - (count ( $arr ) + 1), $item );
	return $arr;
}
/**********************************************
 *
 * PURPOSE: Flatten a deep multidimensional array into a list of its
 * scalar values
 *
 * array array_values_recursive (array array)
 *
 * WARNING: Array keys will be lost
 *
 *********************************************/

function array_values_recursive($array) {
	$arrayValues = array ();
	
	foreach ( $array as $value ) {
		if (is_scalar ( $value ) or is_resource ( $value )) {
			$arrayValues [] = $value;
		} elseif (is_array ( $value )) {
			$arrayValues = array_merge ( $arrayValues, array_values_recursive ( $value ) );
		}
	}
	
	return $arrayValues;
}
if (! function_exists ( 'microtime_float' )) {
	/**
	 * Simple function to replicate PHP 5 behaviour
	 */
	function microtime_float() {
		list ( $usec, $sec ) = explode ( " ", microtime () );
		return (( float ) $usec + ( float ) $sec);
	}
}

if (! function_exists ( 'safe_redirect' )) {
	function safe_redirect($url) {
		
		if (trim ( $url ) == '') {
			return false;
		}
		
		$url = str_ireplace ( 'Location:', '', $url );
		$url = trim ( $url );
		
		if (headers_sent ()) {
			print '<meta http-equiv="refresh" content="0;url=' . $url . '">';
		} else {
			header ( 'Location: ' . $url );
		}
		
		exit ();
	}
}

/////////////////////////////////////////////////////////////////
// Title: execution_time.php
// Description: Displays how long a script took
// http://www.codecall.net
//
// Usage:
//      include execution_time.php
//      In header place 
//				$startTime = slog_time();
//      In footer place 
//				$totalTime = elog_time($startTime);
//              print "Execution Time: $totalTime Seconds";
/////////////////////////////////////////////////////////////////


// Determine Start Time
function slog_time() {
	$mtime = microtime ();
	$mtime = explode ( " ", $mtime );
	$mtime = $mtime [1] + $mtime [0];
	$starttime = $mtime;
	
	// Return our time
	return $startTime;
}

// Determine end time
function elog_time($starttime) {
	$mtime = microtime ();
	$mtime = explode ( " ", $mtime );
	$mtime = $mtime [1] + $mtime [0];
	$endtime = $mtime;
	$totaltime = ($endtime - $starttime);
	
	// Return our display
	return $totalTime;
}

// move a directory and all subdirectories and files (recursive)
// void dirmv( str 'source directory', str 'destination directory' [, bool 'overwrite existing files' [, str 'location within the directory (for recurse)']] )
function dirmv($source, $dest, $overwrite = false, $funcloc = NULL) {
	
	if (is_null ( $funcloc )) {
		$dest .= '/' . strrev ( substr ( strrev ( $source ), 0, strpos ( strrev ( $source ), '/' ) ) );
		$funcloc = '/';
	}
	
	if (! is_dir ( $dest . $funcloc ))
		mkdir ( $dest . $funcloc ); // make subdirectory before subdirectory is copied
	

	if ($handle = opendir ( $source . $funcloc )) { // if the folder exploration is sucsessful, continue
		while ( false !== ($file = readdir ( $handle )) ) { // as long as storing the next file to $file is successful, continue
			if ($file != '.' && $file != '..') {
				$path = $source . $funcloc . $file;
				$path2 = $dest . $funcloc . $file;
				
				if (is_file ( $path )) {
					if (! is_file ( $path2 )) {
						if (! @rename ( $path, $path2 )) {
							// echo '<font color="red">File ('.$path.') could not be moved, likely a permissions problem.</font>';
						}
					} elseif ($overwrite) {
						if (! @unlink ( $path2 )) {
							// echo 'Unable to overwrite file ("'.$path2.'"), likely to be a permissions problem.';
						} else if (! @rename ( $path, $path2 )) {
							// echo '<font color="red">File ('.$path.') could not be moved while overwritting, likely a permissions problem.</font>';
						}
					}
				} elseif (is_dir ( $path )) {
					dirmv ( $source, $dest, $overwrite, $funcloc . $file . '/' ); //recurse!
					rmdir ( $path );
				}
			}
		}
		closedir ( $handle );
	}
}

/*
PHP CSS Browser Selector v0.0.1
Bastian Allgeier (http://bastian-allgeier.de)
http://bastian-allgeier.de/css_browser_selector
License: http://creativecommons.org/licenses/by/2.5/
Credits: This is a php port from Rafael Lima's original Javascript CSS Browser Selector: http://rafael.adm.br/css_browser_selector
*/

function css_browser_selector($ua = null) {
	$ua = ($ua) ? strtolower ( $ua ) : strtolower ( $_SERVER ['HTTP_USER_AGENT'] );
	
	$g = 'gecko';
	$w = 'webkit';
	$s = 'safari';
	$b = array ();
	
	// browser
	if (! preg_match ( '/opera|webtv/i', $ua ) && preg_match ( '/msie\s(\d)/', $ua, $array )) {
		$b [] = 'ie ie' . $array [1];
	} else if (strstr ( $ua, 'firefox/2' )) {
		$b [] = $g . ' ff2';
	} else if (strstr ( $ua, 'firefox/3.5' )) {
		$b [] = $g . ' ff3 ff3_5';
	} else if (strstr ( $ua, 'firefox/3' )) {
		$b [] = $g . ' ff3';
	} else if (strstr ( $ua, 'gecko/' )) {
		$b [] = $g;
	} else if (preg_match ( '/opera(\s|\/)(\d+)/', $ua, $array )) {
		$b [] = 'opera opera' . $array [2];
	} else if (strstr ( $ua, 'konqueror' )) {
		$b [] = 'konqueror';
	} else if (strstr ( $ua, 'chrome' )) {
		$b [] = $w . ' ' . $s . ' chrome';
	} else if (strstr ( $ua, 'iron' )) {
		$b [] = $w . ' ' . $s . ' iron';
	} else if (strstr ( $ua, 'applewebkit/' )) {
		$b [] = (preg_match ( '/version\/(\d+)/i', $ua, $array )) ? $w . ' ' . $s . ' ' . $s . $array [1] : $w . ' ' . $s;
	} else if (strstr ( $ua, 'mozilla/' )) {
		$b [] = $g;
	}
	
	// platform				
	if (strstr ( $ua, 'j2me' )) {
		$b [] = 'mobile';
	} else if (strstr ( $ua, 'iphone' )) {
		$b [] = 'iphone';
	} else if (strstr ( $ua, 'ipod' )) {
		$b [] = 'ipod';
	} else if (strstr ( $ua, 'mac' )) {
		$b [] = 'mac';
	} else if (strstr ( $ua, 'darwin' )) {
		$b [] = 'mac';
	} else if (strstr ( $ua, 'webtv' )) {
		$b [] = 'webtv';
	} else if (strstr ( $ua, 'win' )) {
		$b [] = 'win';
	} else if (strstr ( $ua, 'freebsd' )) {
		$b [] = 'freebsd';
	} else if (strstr ( $ua, 'x11' ) || strstr ( $ua, 'linux' )) {
		$b [] = 'linux';
	}
	
	return join ( ' ', $b );

}
function normalize_path($path, $slash_it = true) {
	
	// DIRECTORY_SEPARATOR is a system variable
	// which contains the right slash for the current 
	// system (windows = \ or linux = /)
	

	$path_original = $path;
	$s = DIRECTORY_SEPARATOR;
	$path = preg_replace ( '/[\/\\\]/', $s, $path );
	$path = preg_replace ( '/' . $s . '$/', '', $path ) . $s;
	
	$path = str_replace ( $s . $s, $s, $path );
	if (strval ( $path ) == '') {
		$path = $path_original;
	}
	if ($slash_it == false) {
		$path = rtrim ( $path, DIRECTORY_SEPARATOR );
	}
	if (strval ( $path ) == '') {
		$path = $path_original;
	}
	return $path;
}
function base64_to_file($data, $target) {
	//$data = 'your base64 data';
	//$target = 'localfile.data';
	touch ( $target );
	if (is_writable ( $target ) == false) {
		exit ( "$target is not writable" );
	}
	
	//$whandle = fopen ( $target, 'wb' );
	//stream_filter_append ( $whandle, 'convert.base64-decode', STREAM_FILTER_WRITE );
	

	file_put_contents ( $whandle, base64_decode ( $data ) );
	
//fclose ( $whandle );
}

if (! function_exists ( 'getCurentURL' )) {
	
	function getCurentURL() {
		
		$pageURL = 'http';
		
		if ($_SERVER ["HTTPS"] == "on") {
			
			$pageURL .= "s";
		
		}
		
		$pageURL .= "://";
		
		if ($_SERVER ["SERVER_PORT"] != "80") {
			
			$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
		
		} else {
			
			$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
		
		}
		
		return $pageURL;
	
	}

}

if (! function_exists ( 'getParamFromURL' )) {
	
	function getParamFromURL($param = false) {
		
		$url = getCurentURL ();
		
		$site = site_url ();
		
		$url = str_ireplace ( $site, '', $url );
		
		$segs = explode ( '/', $url );
		
		foreach ( $segs as $segment ) {
			
			$segment = explode ( ':', $segment );
			
			if ($segment [0] == $param) {
				
				return $segment [1];
			
			}
		
		}
		
		return false;
	
	}

}

function isAjax() {
	return (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && ($_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

if (! function_exists ( 'pathToURL' )) {
	
	function pathToURL($path) {
		
		//var_dump($path);
		

		$path = str_ireplace ( ROOTPATH, '', $path );
		$path = str_ireplace ( '\\', '/', $path );
		//var_dump($path);
		return site_url ( $path );
	
	}

}

if (! function_exists ( 'dirToURL' )) {
	
	function dirToURL($path) {
		
		return pathToURL ( $path );
	
	}

}

// $arrays - Array of arrays to intersect.


if (! function_exists ( 'getParamFromURL' )) {
	
	function getParamFromURL($param, $param_sub_position = false) {
		
		//$segs = $this->uri->segment_array ();
		if ($_POST) {
			
			if ($_POST ['search_by_keyword']) {
				
				if ($param == 'keyword') {
					
					return $_POST ['search_by_keyword'];
				
				}
			
			}
		
		}
		
		$url = uri_string ();
		
		$rem = site_url ();
		
		$url = str_ireplace ( $rem, '', $url );
		
		$segs = explode ( '/', $url );
		
		foreach ( $segs as $segment ) {
			
			$seg1 = explode ( ':', $segment );
			
			//	var_dump($seg1);
			if (($seg1 [0]) == ($param)) {
				
				//if (stristr ( $segment, $param . ':' ) == true) {
				if ($param_sub_position == false) {
					
					$the_param = str_ireplace ( $param . ':', '', $segment );
					
					if ($param == 'custom_fields_criteria') {
						
						$the_param1 = base64_decode ( $the_param );
						
						$the_param1 = unserialize ( $the_param1 );
						
						return $the_param1;
					
					}
					
					return $the_param;
				
				} else {
					
					$the_param = str_ireplace ( $param . ':', '', $segment );
					
					$params_list = explode ( ',', $the_param );
					
					if ($param == 'custom_fields_criteria') {
						
						$the_param1 = base64_decode ( $the_param );
						
						$the_param1 = unserialize ( $the_param1 );
						
						return $the_param1;
					
					}
					
					//$param_value = $params_list [$param_sub_position - 1];
					//$param_value = $the_param;
					return $the_param;
				
				}
			
			}
		
		}
	
	}

}
if (! function_exists ( 'getParamFromURL' )) {
	function getParamFromURL($param = false) {
		$url = getCurentURL ();
		$site = site_url ();
		$url = str_ireplace ( $site, '', $url );
		$segs = explode ( '/', $url );
		foreach ( $segs as $segment ) {
			$segment = explode ( ':', $segment );
			if ($segment [0] == $param) {
				return $segment [1];
			}
		}
		return false;
	}
}

if (! function_exists ( 'pathToURL' )) {
	function pathToURL($path) {
		//var_dump($path);
		$path = str_ireplace ( ROOTPATH, '', $path );
		//var_dump($path);
		return site_url ( $path );
	
	}
}

if (! function_exists ( 'dirToURL' )) {
	function dirToURL($path) {
		return pathToURL ( $path );
	}
}

// $arrays - Array of arrays to intersect.


function calculate_intersection($arrays) {
	$intersection = Array ();
	
	for($checked_item = 0; $checked_item < count ( $arrays [0] ); $checked_item ++) {
		$occurrence = 1;
		
		for($compared_array = 1; $compared_array < count ( $arrays ); $compared_array ++) {
			for($compared_item = 0; $compared_item < count ( $arrays [$compared_array] ); $compared_item ++) {
				if ($arrays [0] [$checked_item] == $arrays [$compared_array] [$compared_item]) {
					$occurrence ++;
					
					if ($occurrence == count ( $arrays )) {
						$intersection [] = $arrays [0] [$checked_item];
					}
				}
			}
		}
	}
	
	return $intersection;
}

/**
 * @desc constructWhere Functions used by the jqgrid library ... must staty here
 * @version 1.0
 * @since Version 1.0
 */
if (! function_exists ( 'constructWhere' )) {
	function constructWhere($s) {
		$qwery = "";
		//['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']
		$qopers = array ('eq' => " = ", 'ne' => " <> ", 'lt' => " < ", 'le' => " <= ", 'gt' => " > ", 'ge' => " >= ", 'bw' => " LIKE ", 'bn' => " NOT LIKE ", 'in' => " IN ", 'ni' => " NOT IN ", 'ew' => " LIKE ", 'en' => " NOT LIKE ", 'cn' => " LIKE ", 'nc' => " NOT LIKE " );
		if ($s) {
			$jsona = json_decode ( $s, true );
			if (is_array ( $jsona )) {
				$gopr = $jsona ['groupOp'];
				$rules = $jsona ['rules'];
				$i = 0;
				foreach ( $rules as $key => $val ) {
					$field = $val ['field'];
					$op = $val ['op'];
					$v = $val ['data'];
					if ($v && $op) {
						$i ++;
						// ToSql in this case is absolutley needed
						$v = ToSql ( $field, $op, $v );
						if (i == 1)
							$qwery = " AND ";
						else
							$qwery .= " " . $gopr . " ";
						switch ($op) {
							// in need other thing
							case 'in' :
							case 'ni' :
								$qwery .= $field . $qopers [$op] . " (" . $v . ")";
								break;
							default :
								$qwery .= $field . $qopers [$op] . $v;
						}
					}
				}
			}
		}
		return $qwery;
	}
}

/**
 * @desc ToSql Functions used by the jqgrid library ... must stay here
 * @version 1.0
 * @since Version 1.0
 */
if (! function_exists ( 'ToSql' )) {
	function ToSql($field, $oper, $val) {
		//@todo we need here more advanced checking using the type of the field - i.e. integer, string, float
		switch ($field) {
			case 'id' :
				return intval ( $val );
				break;
			case 'amount' :
			case 'tax' :
			case 'total' :
				return floatval ( $val );
				break;
			default :
				//mysql_real_escape_string is better
				if ($oper == 'bw' || $oper == 'bn')
					return " '" . addslashes ( $val ) . "%' ";
				else if ($oper == 'ew' || $oper == 'en')
					return "'%" . addcslashes ( $val ) . "' ";
				else if ($oper == 'cn' || $oper == 'nc')
					return " '%" . addslashes ( $val ) . "%' ";
				else
					return " '" . addslashes ( $val ) . "' ";
		}
	}
}
function substring_between($haystack, $start, $end) {
	if (strpos ( $haystack, $start ) === false || strpos ( $haystack, $end ) === false) {
		return false;
	} else {
		$start_position = strpos ( $haystack, $start ) + strlen ( $start );
		$end_position = strpos ( $haystack, $end );
		return substr ( $haystack, $start_position, $end_position - $start_position );
	}
}

/**
 * @desc Strip Functions used by the jqgrid library ... must stay here
 * @version 1.0
 * @since Version 1.0
 */
if (! function_exists ( 'Strip' )) {
	function Strip($value) {
		if (get_magic_quotes_gpc () != 0) {
			if (is_array ( $value ))
				if (array_is_associative ( $value )) {
					foreach ( $value as $k => $v )
						$tmp_val [$k] = stripslashes ( $v );
					$value = $tmp_val;
				} else
					for($j = 0; $j < sizeof ( $value ); $j ++)
						$value [$j] = stripslashes ( $value [$j] );
			else
				$value = stripslashes ( $value );
		}
		return $value;
	}
}
/**
 * @desc array_is_associative Functions used by the jqgrid library ... must stay here
 * @version 1.0
 * @since Version 1.0
 */
if (! function_exists ( 'array_is_associative' )) {
	function array_is_associative($array) {
		if (is_array ( $array ) && ! empty ( $array )) {
			for($iterator = count ( $array ) - 1; $iterator; $iterator --) {
				if (! array_key_exists ( $iterator, $array )) {
					return true;
				}
			}
			return ! array_key_exists ( 0, $array );
		}
		return false;
	}

}
if (! function_exists ( 'array_trim' )) {
	function array_trim($ar) {
		foreach ( $ar as $key )
			if (empty ( $ar [$key] ))
				unset ( $ar [$key] );
		return $ar;
	}
	;
}
if (! function_exists ( 'trimArray' )) {
	
	/**
	 * Trims a entire array recursivly.
	 *
	 * @author      Jonas John
	 * @version     0.2
	 * @link        http://www.jonasjohn.de/snippets/php/trim-array.htm
	 * @param       array      $Input      Input array
	 */
	
	function trimArray($Input) {
		
		if (! is_array ( $Input ))
			
			return trim ( $Input );
		
		return array_map ( 'trimArray', $Input );
	
	}

}

if (! function_exists ( 'dbg' )) {
	
	function dbg($text, $end = false) {
		
		if (defined ( 'DEBUG_INFO_IN_TEMPLATE' )) {
			if ($end == true) {
				$end1 = ' end of ';
			}
			
			$divs = getParamFromURL ( 'debug' );
			
			if ($divs) {
				//$text = wordwrap($text,20,"<br />\n");
				echo '<div class="debug"> ' . $end1 . ' debug: ' . $text . ' </div>';
			} else {
				echo '<!-- ' . $end1 . ' debug: ' . $text . '  -->';
			}
		
		}
	
	}

}

function mb_trimArray($Input) {
	
	if (! is_array ( $Input ))
		
		return mb_trim ( $Input );
	
	return array_map ( 'mb_trimArray', $Input );

}

if (! function_exists ( 'array_unique_FULL' )) {
	
	function array_unique_FULL($array) {
		
		foreach ( $array as $k => $v ) {
			
			if (is_array ( $v )) {
				
				$ret = array_unique_FULL ( array_merge ( $ret, $v ) );
			
			} else {
				
				$ret [$k] = $v;
			
			}
		
		} //for
		return array_unique ( $ret );
	
	}

}

/*
* This function deletes the given element from a one-dimension array
* Parameters: $array:    the array (in/out)
*             $deleteIt: the value which we would like to delete
*             $useOldKeys: if it is false then the function will re-index the array (from 0, 1, ...)
*                          if it is true: the function will keep the old keys
* Returns true, if this value was in the array, otherwise false (in this case the array is same as before)
*/

function deleteFromOneDumensionalArray(& $array, $deleteIt, $useOldKeys = FALSE) {
	
	if (is_array ( $array ) != false) {
		
		$key = array_search ( $deleteIt, $array, TRUE );
	
	}
	
	if ($key === FALSE)
		
		return FALSE;
	
	unset ( $array [$key] );
	
	if (! $useOldKeys) {
		
		if (is_array ( $array ) != false) {
			
			$array = array_values ( $array );
		
		}
	
	}
	
	return TRUE;

}

function remove_newlines($old_string) {
	
	$new_string = preg_replace ( "/\n|\r\n|\r$/", "", $old_string );
	
	return $new_string;

}

if (! function_exists ( 'mb_trim' )) {
	
	/**
	 * Trim characters from either (or both) ends of a string in a way that is
	 * multibyte-friendly.
	 *
	 * Mostly, this behaves exactly like trim() would: for example supplying 'abc' as
	 * the charlist will trim all 'a', 'b' and 'c' chars from the string, with, of
	 * course, the added bonus that you can put unicode characters in the charlist.
	 *
	 * We are using a PCRE character-class to do the trimming in a unicode-aware
	 * way, so we must escape ^, \, - and ] which have special meanings here.
	 * As you would expect, a single \ in the charlist is interpretted as
	 * "trim backslashes" (and duly escaped into a double-\ ). Under most circumstances
	 * you can ignore this detail.
	 *
	 * As a bonus, however, we also allow PCRE special character-classes (such as '\s')
	 * because they can be extremely useful when dealing with UCS. '\pZ', for example,
	 * matches every 'separator' character defined in Unicode, including non-breaking
	 * and zero-width spaces.
	 *
	 * It doesn't make sense to have two or more of the same character in a character
	 * class, therefore we interpret a double \ in the character list to mean a
	 * single \ in the regex, allowing you to safely mix normal characters with PCRE
	 * special classes.
	 *
	 * *Be careful* when using this bonus feature, as PHP also interprets backslashes
	 * as escape characters before they are even seen by the regex. Therefore, to
	 * specify '\\s' in the regex (which will be converted to the special character
	 * class '\s' for trimming), you will usually have to put *4* backslashes in the
	 * PHP code - as you can see from the default value of $charlist.
	 *
	 * @param string
	 * @param charlist list of characters to remove from the ends of this string.
	 * @param boolean trim the left?
	 * @param boolean trim the right?
	 * @return String
	 */
	
	function mb_trim($string, $charlist = '\\\\s', $ltrim = true, $rtrim = true) {
		
		$both_ends = $ltrim && $rtrim;
		
		$char_class_inner = preg_replace ( array ('/[\^\-\]\\\]/S', '/\\\{4}/S' ), array ('\\\\\\0', '\\' ), $charlist );
		
		$work_horse = '[' . $char_class_inner . ']+';
		
		$ltrim && $left_pattern = '^' . $work_horse;
		
		$rtrim && $right_pattern = $work_horse . '$';
		
		if ($both_ends) {
			
			$pattern_middle = $left_pattern . '|' . $right_pattern;
		
		} elseif ($ltrim) {
			
			$pattern_middle = $left_pattern;
		
		} else {
			
			$pattern_middle = $right_pattern;
		
		}
		
		return preg_replace ( "/$pattern_middle/usSD", '', $string );
	
	}

}

if (! function_exists ( 'get_percent_from_amount' )) {
	/**
	 * @desc calculate precent
	 * @param $num_amount
	 * @param $num_total
	 * @return percent
	 * @author		Peter Ivanov
	 * @version 1.0
	 * @since Version 1.0
	 * */
	function get_percent_from_amount($num_amount, $num_total) {
		$count1 = $num_amount / $num_total;
		$count2 = $count1 * 100;
		$count = number_format ( $count2, 0 );
		return $count;
	}
}
function stripFromArray($value) {
	
	return is_array ( $value ) ? array_map ( 'stripFromArray', $value ) : strip_tags ( $value );

}

/**
 * @desc If you found yourself in need of a multidimensional array in_array like function you can use the one below. Works in a fair amount of time
 * @param $elem
 * @param $array
 * @return array
 * @author		Microweber Dev Team
 * @version 1.0
 * @since Version 1.0
 * */
function in_multiarray($elem, $array) {
	$top = sizeof ( $array ) - 1;
	$bottom = 0;
	while ( $bottom <= $top ) {
		if ($array [$bottom] == $elem)
			return true;
		else if (is_array ( $array [$bottom] ))
			if (in_multiarray ( $elem, ($array [$bottom]) ))
				return true;
		
		$bottom ++;
	}
	return false;
}

/**
 * @desc A little function to use an array of needles:
 * @param $elem
 * @param $array
 * @return array
 * @author		Microweber Dev Team
 * @version 1.0
 * @since Version 1.0
 * */
function array_in_array($needles, $haystack) {
	
	foreach ( $needles as $needle ) {
		
		if (in_array ( $needle, $haystack )) {
			return true;
		}
	}
	
	return false;
}

function htmlGetTagFromHtmlString($attr, $value, $xml, $tag = null) {
	if (is_null ( $tag ))
		$tag = '\w+';
	else
		$tag = preg_quote ( $tag );
	
	$attr = preg_quote ( $attr );
	$value = preg_quote ( $value );
	
	$tag_regex = "/<(" . $tag . ")[^>]*$attr\s*=\s*" . "(['\"])$value\\2[^>]*>(.*?)<\/\\1>/";
	
	preg_match_all ( $tag_regex, $xml, $matches, PREG_PATTERN_ORDER );
	
	return $matches [3];
}

function htmlentitiesArray($value) {
	
	return is_array ( $value ) ? array_map ( 'htmlentitiesArray', $value ) : htmlentities ( $value );

}
function htmlentitiesdecodeArray($value) {
	
	return is_array ( $value ) ? array_map ( 'htmlentitiesdecodeArray', $value ) : html_entity_decode ( $value );

}

/**
 * A recursive version of htmlspecialchars() for arrays and strings.
 *
 */

function htmlspecialchars_deep($mixed, $quote_style = ENT_QUOTES, $charset = 'UTF-8') {
	if (is_array ( $mixed )) {
		foreach ( $mixed as $key => $value ) {
			$mixed [$key] = htmlspecialchars_deep ( $value, $quote_style, $charset );
		}
	} elseif (is_string ( $mixed )) {
		$mixed = htmlspecialchars ( htmlspecialchars_decode ( $mixed, $quote_style ), $quote_style, $charset );
	}
	return $mixed;
}

function htmlspecialchars_deep_decode($mixed, $quote_style = ENT_QUOTES, $charset = 'UTF-8') {
	if (is_array ( $mixed )) {
		foreach ( $mixed as $key => $value ) {
			$mixed [$key] = htmlspecialchars_deep_decode ( $value, $quote_style, $charset );
		}
	} elseif (is_string ( $mixed )) {
		$mixed = (htmlspecialchars_decode ( $mixed, $quote_style ));
	}
	return $mixed;
}

//var_dump ( $_GLOBALS  );


/**
 * Remove the directory and its content (all files and subdirectories).
 * @param string $dir the directory name
 */
function rmrf($dir) {
	
	if (substr ( $dir, - 1 ) != DIRECTORY_SEPARATOR) {
		$dir = $dir . DIRECTORY_SEPARATOR;
	}
	
	if (stristr ( $dir, '*' ) == false) {
		$dir = $dir . "*";
	}
	//var_dump ( $dir );
	foreach ( glob ( $dir ) as $file ) {
		if (is_dir ( $file )) {
			rmrf ( "$file" . DIRECTORY_SEPARATOR );
			@rmdir ( $file );
		} else {
			@unlink ( $file );
		}
	}

}

// to use this function to totally remove a directory, write:
// recursive_remove_directory('path/to/directory/to/delete');


// to use this function to empty a directory, write:
// recursive_remove_directory('path/to/full_directory',TRUE);
function recursive_remove_directory($directory, $empty = FALSE) {
	rmrf ( $directory );

}

/**
 * Strip punctuation from text.
 */

function strip_punctuation($text) {
	
	$cleanUnicodeStr = trim ( preg_replace ( '#[^\p{L}\p{N}]+#u', ' ', $text ) );
	return $cleanUnicodeStr;

}

function array_remove_empty($arr) {
	
	$narr = array ();
	
	while ( list ( $key, $val ) = each ( $arr ) ) {
		
		if (is_array ( $val )) {
			
			$val = array_remove_empty ( $val );
			
			// does the result array contain anything?
			if (count ( $val ) != 0) {
				
				// yes :-)
				$narr [$key] = $val;
			
			}
		
		} else {
			
			if (trim ( $val ) != "") {
				
				$narr [$key] = $val;
			
			}
		
		}
	
	}
	
	unset ( $arr );
	
	return $narr;

}

function visitorIP() {
	
	if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ))
		
		$TheIp = $_SERVER ['HTTP_X_FORWARDED_FOR'];
	else
		
		$TheIp = $_SERVER ['REMOTE_ADDR'];
	
	return trim ( $TheIp );

}

/*
*
*   I needed a function which compares $arr1 with $arr2 and return all values from $arr2 that aren't in $arr1.
Additionally the function has to compares multiarrays to.
Here is it:

*
* */

function arr_diff($a1, $a2) {
	
	$ar = array ();
	
	foreach ( $a2 as $k => $v ) {
		
		if (! is_array ( $v )) {
			
			if ($v !== $a1 [$k])
				
				$ar [$k] = $v;
		
		} else {
			
			if ($arr = arr_diff ( $a1 [$k], $a2 [$k] ))
				
				$ar [$k] = $arr;
		
		}
	
	}
	
	return $ar;

}

function mb_str_replace($needle, $replacement, $haystack) {
	
	$needle_len = mb_strlen ( $needle );
	
	$replacement_len = mb_strlen ( $replacement );
	
	$pos = @mb_strpos ( $haystack, $needle );
	
	while ( $pos !== false ) {
		
		$haystack = mb_substr ( $haystack, 0, $pos ) . $replacement . mb_substr ( $haystack, $pos + $needle_len );
		
		$pos = mb_strpos ( $haystack, $needle, $pos + $replacement_len );
	
	}
	
	return $haystack;

}
function utfString($content) {
	if (! mb_check_encoding ( $content, 'UTF-8' ) or ! ($content === mb_convert_encoding ( mb_convert_encoding ( $content, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32' ))) {
		
		$content = mb_convert_encoding ( $content, 'UTF-8' );
		
		if (mb_check_encoding ( $content, 'UTF-8' )) {
			// log('Converted to UTF-8'); 
		} else {
			// log('Could not converted to UTF-8'); 
		}
	}
	return $content;
}

/**
 * Makes directory, returns TRUE if exists or made
 *
 * @param string $pathname The directory path.
 * @return boolean returns TRUE if exists or made or FALSE on failure.
 */

function mkdir_recursive($pathname) {
	
	if ($pathname == '') {
		return false;
	}
	
	is_dir ( dirname ( $pathname ) ) || mkdir_recursive ( dirname ( $pathname ) );
	
	return is_dir ( $pathname ) || mkdir ( $pathname );

}

if (! function_exists ( 'mb_ucfirst' ) && function_exists ( 'mb_substr' )) {
	
	function mb_ucfirst($string) {
		
		$string = mb_strtoupper ( mb_substr ( $string, 0, 1 ) ) . mb_substr ( $string, 1 );
		
		return $string;
	
	}

}

function getFilenameWithoutExt($filename) {
	
	$pos = strripos ( $filename, '.' );
	
	if ($pos === false) {
		
		return $filename;
	
	} else {
		
		return substr ( $filename, 0, $pos );
	
	}

}

function readDirIntoArray($dir, $dirs_or_files = 'both') {
	
	if ($handle = opendir ( $dir )) {
		
		$files = array ();
		
		while ( false !== ($file = readdir ( $handle )) ) {
			
			//var_dump($file);
			

			if ($dirs_or_files == 'files') {
				
				if (is_file ( $dir . $file ) == true) {
					
					$files [$file] = preg_replace ( '/[^0-9]/', '', $file ); # Timestamps may not be unique, file names are.
				

				}
			
			} else if ($dirs_or_files == 'dirs') {
				
				if (is_dir ( $dir . $file ) == true) {
					
					$files [$file] = preg_replace ( '/[^0-9]/', '', $file ); # Timestamps may not be unique, file names are.
				

				}
			
			} else {
				
				$files [$file] = preg_replace ( '/[^0-9]/', '', $file ); # Timestamps may not be unique, file names are.
			

			}
		
		}
		
		closedir ( $handle );
		
		arsort ( $files );
		
		$files = array_keys ( $files );
		
		return $files;
	
	}

}

/**
 * @brief Returns an array with all values lowercased or uppercased.
 * @return array Returns an array with all values lowercased or uppercased.
 * @param object $input The array to work on
 * @param int $case [optional] Either \c CASE_UPPER or \c CASE_LOWER (default).
 */

function array_change_value_case(array $input, $case = CASE_LOWER) {
	
	switch ($case) {
		
		case CASE_LOWER :
			
			return array_map ( 'mb_strtolower', $input );
			
			break;
		
		case CASE_UPPER :
			
			return array_map ( 'mb_strtoupper', $input );
			
			break;
		
		default :
			
			trigger_error ( 'Case is not valid, CASE_LOWER or CASE_UPPER only', E_USER_ERROR );
			
			return false;
	
	}

}

/* Example usage

$w="abracadabra";

print str_replace_count("abra","----",$w,2);

*/

function str_replace_count($search, $replace, $subject, $times) {
	
	$subject_original = $subject;
	
	$len = strlen ( $search );
	
	$pos = 0;
	
	for($i = 1; $i <= $times; $i ++) {
		
		$pos = strpos ( $subject, $search, $pos );
		
		if ($pos !== false) {
			
			$subject = substr ( $subject_original, 0, $pos );
			
			$subject .= $replace;
			
			$subject .= substr ( $subject_original, $pos + $len );
			
			$subject_original = $subject;
		
		} else {
			
			break;
		
		}
	
	}
	
	return ($subject);

}

#


/**
   NAME        : autolink()
   VERSION     : 1.0
   AUTHOR      : J de Silva
   DESCRIPTION : returns VOID; handles converting
                 URLs into clickable links off a string.
   TYPE        : functions
 */

function autolink(&$text, $target = '_blank', $nofollow = true) {
	
	// grab anything that looks like a URL...
	$urls = _autolink_find_URLS ( $text );
	
	if (! empty ( $urls )) // i.e. there were some URLS found in the text
{
		
		array_walk ( $urls, '_autolink_create_html_tags', array ('target' => $target, 'nofollow' => $nofollow ) );
		
		$text = strtr ( $text, $urls );
	
	}

}

function _autolink_find_URLS($text) {
	
	// build the patterns
	$scheme = '(http:\/\/|https:\/\/)';
	
	$www = 'www\.';
	
	$ip = '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
	
	$subdomain = '[-a-z0-9_]+\.';
	
	$name = '[a-z][-a-z0-9]+\.';
	
	$tld = '[a-z]+(\.[a-z]{2,2})?';
	
	$the_rest = '\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1}';
	
	$pattern = "$scheme?(?(1)($ip|($subdomain)?$name$tld)|($www$name$tld))$the_rest";
	
	$pattern = '/' . $pattern . '/is';
	
	$c = preg_match_all ( $pattern, $text, $m );
	
	unset ( $text, $scheme, $www, $ip, $subdomain, $name, $tld, $the_rest, $pattern );
	
	if ($c) {
		
		return (array_flip ( $m [0] ));
	
	}
	
	return (array ());

}

function _autolink_create_html_tags(&$value, $key, $other = null) {
	
	$target = $nofollow = null;
	
	if (is_array ( $other )) {
		
		$target = ($other ['target'] ? " target=\"$other[target]\"" : null);
		
		// see: http://www.google.com/googleblog/2005/01/preventing-comment-spam.html
		$nofollow = ($other ['nofollow'] ? ' rel="nofollow"' : null);
	
	}
	
	$value = "<a href=\"$key\"$target$nofollow>$key</a>";

}

/**

 * Fetch the number of followers from twitter api

 *

 * @author Peter Ivanov <peter@ooyes.net> * @copyright    http://www.ooyes.net

 * @version    0.2

 * @link http://www.ooyes.net

 * @param string $username

 * @return string

 */

function twitter_followers_counter($username) {
	
	$cache_file = CACHEDIR . 'twitter_followers_counter_' . md5 ( $username );
	
	if (is_file ( $cache_file ) == false) {
		
		$cache_file_time = strtotime ( '1984-01-11 07:15' );
	
	} else {
		
		$cache_file_time = filemtime ( $cache_file );
	
	}
	
	$now = strtotime ( date ( 'Y-m-d H:i:s' ) );
	
	$api_call = $cache_file_time;
	
	$difference = $now - $api_call;
	
	$api_time_seconds = 1800;
	
	if ($difference >= $api_time_seconds) {
		
		$api_page = 'http://twitter.com/users/show/' . $username;
		
		$xml = file_get_contents ( $api_page );
		
		$profile = new SimpleXMLElement ( $xml );
		
		$count = $profile->followers_count;
		
		if (is_file ( $cache_file ) == true) {
			
			@unlink ( $cache_file );
		
		}
		
		touch ( $cache_file );
		
		file_put_contents ( $cache_file, strval ( $count ) );
		
		return strval ( $count );
	
	} else {
		
		$count = file_get_contents ( $cache_file );
		
		return strval ( $count );
	
	}

}

function w3cDate($time = NULL) {
	
	if (empty ( $time ))
		
		$time = time ();
	
	$offset = date ( "O", $time );
	
	return date ( "Y-m-d\TH:i:s", $time ) . substr ( $offset, 0, 3 ) . ":" . substr ( $offset, - 2 );

}

class CurlTool {
	
	public static $userAgents = array ('FireFox3' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0', 'GoogleBot' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'IE7' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', 'Netscape' => 'Mozilla/4.8 [en] (Windows NT 6.0; U)', 'Opera' => 'Opera/9.25 (Windows NT 6.0; U; en)' );
	
	public static $options = array (CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0', CURLOPT_AUTOREFERER => true, CURLOPT_COOKIEFILE => '', CURLOPT_FOLLOWLOCATION => true );
	
	private static $proxyServers = array ();
	
	private static $proxyCount = 0;
	
	private static $currentProxyIndex = 0;
	
	public static function addProxyServer($url) {
		
		self::$proxyServers [] = $url;
		
		++ self::$proxyCount;
	
	}
	
	public static function fetchContent($url, $verbose = false) {
		
		if (($curl = curl_init ( $url )) == false) {
			
			throw new Exception ( "curl_init error for url $url." );
		
		}
		
		if (self::$proxyCount > 0) {
			
			$proxy = self::$proxyServers [self::$currentProxyIndex ++ % self::$proxyCount];
			
			curl_setopt ( $curl, CURLOPT_PROXY, $proxy );
			
			if ($verbose === true) {
				
				echo "Reading $url [Proxy: $proxy] ... ";
			
			}
		
		} else if ($verbose === true) {
			
			echo "Reading $url ... ";
		
		}
		
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		
		curl_setopt_array ( $curl, self::$options );
		
		$content = curl_exec ( $curl );
		
		if ($content === false) {
			
			throw new Exception ( "curl_exec error for url $url." );
		
		}
		
		curl_close ( $curl );
		
		if ($verbose === true) {
			
		////echo "Done.\n";
		

		}
		
		$content = preg_replace ( '#\n+#', ' ', $content );
		
		$content = preg_replace ( '#\s+#', ' ', $content );
		
		return $content;
	
	}
	
	public static function downloadFile($url, $fileName, $verbose = false) {
		
		if (($curl = curl_init ( $url )) == false) {
			
		//throw new Exception ( "curl_init error for url $url." );
		

		}
		
		if (self::$proxyCount > 0) {
			
			$proxy = self::$proxyServers [self::$currentProxyIndex ++ % self::$proxyCount];
			
			curl_setopt ( $curl, CURLOPT_PROXY, $proxy );
			
			if ($verbose === true) {
				
			//	echo "Downloading $url [Proxy: $proxy] ... ";
			

			}
		
		} else if ($verbose === true) {
			
			echo "Downloading $url ... ";
		
		}
		
		curl_setopt_array ( $curl, self::$options );
		
		if (substr ( $fileName, - 1 ) == '/') {
			
			$targetDir = $fileName;
			
			$fileName = tempnam ( sys_get_temp_dir (), 'c_' );
		
		}
		
		if (($fp = fopen ( $fileName, "wb" )) === false) {
			
		//throw new Exception ( "fopen error for filename $fileName" );
		

		}
		
		curl_setopt ( $curl, CURLOPT_FILE, $fp );
		
		curl_setopt ( $curl, CURLOPT_BINARYTRANSFER, true );
		
		if (curl_exec ( $curl ) === false) {
			
			@fclose ( $fp );
			
			@unlink ( $fileName );
			
		//throw new Exception ( "curl_exec error for url $url." );
		

		} elseif (isset ( $targetDir )) {
			
			$eurl = curl_getinfo ( $curl, CURLINFO_EFFECTIVE_URL );
			
			preg_match ( '#^.*/(.+)$#', $eurl, $match );
			
			fclose ( $fp );
			
			rename ( $fileName, "$targetDir{$match[1]}" );
			
			$fileName = "$targetDir{$match[1]}";
		
		} else {
			
			fclose ( $fp );
		
		}
		
		curl_close ( $curl );
		
		if ($verbose === true) {
			
			echo "Done.\n";
		
		}
		
		return $fileName;
	
	}

}

/**
 * Convert a localized number string into a floating point number
 *
 * @param      string $sNumber The localized number string to convert.
 * @return     float The converted number.
 */
function str2num($sNumber) {
	$aConventions = localeConv ();
	$sNumber = trim ( ( string ) $sNumber );
	$bIsNegative = (0 === $aConventions ['n_sign_posn'] && '(' === $sNumber {0} && ')' === $sNumber {strlen ( $sNumber ) - 1});
	$sCharacters = $aConventions ['decimal_point'] . $aConventions ['mon_decimal_point'] . $aConventions ['negative_sign'];
	$sNumber = preg_replace ( '/[^' . preg_quote ( $sCharacters ) . '\d]+/', '', trim ( ( string ) $sNumber ) );
	$iLength = strlen ( $sNumber );
	if (strlen ( $aConventions ['decimal_point'] )) {
		$sNumber = str_replace ( $aConventions ['decimal_point'], '.', $sNumber );
	}
	if (strlen ( $aConventions ['mon_decimal_point'] )) {
		$sNumber = str_replace ( $aConventions ['mon_decimal_point'], '.', $sNumber );
	}
	$sNegativeSign = $aConventions ['negative_sign'];
	if (strlen ( $sNegativeSign ) && 0 !== $aConventions ['n_sign_posn']) {
		$bIsNegative = ($sNegativeSign === $sNumber {0} || $sNegativeSign === $sNumber {$iLength - 1});
		if ($bIsNegative) {
			$sNumber = str_replace ( $aConventions ['negative_sign'], '', $sNumber );
		}
	}
	$fNumber = ( float ) $sNumber;
	if ($bIsNegative) {
		$fNumber = - $fNumber;
	}
	return $fNumber;
}

function floatvalue($value) {
	return floatval ( preg_replace ( '#^([-]*[0-9\.,\' ]+?)((\.|,){1}([0-9-]{1,2}))*$#e', "str_replace(array('.', ',', \"'\", ' '), '', '\\1') . '.\\4'", $value ) );
}

function parseFloat($ptString) {
	if (strlen ( $ptString ) == 0) {
		return false;
	}
	
	$pString = str_replace ( " ", "", $ptString );
	
	if (substr_count ( $pString, "," ) > 1)
		$pString = str_replace ( ",", "", $pString );
	
	if (substr_count ( $pString, "." ) > 1)
		$pString = str_replace ( ".", "", $pString );
	
	$pregResult = array ();
	
	$commaset = strpos ( $pString, ',' );
	if ($commaset === false) {
		$commaset = - 1;
	}
	
	$pointset = strpos ( $pString, '.' );
	if ($pointset === false) {
		$pointset = - 1;
	}
	
	$pregResultA = array ();
	$pregResultB = array ();
	
	if ($pointset < $commaset) {
		preg_match ( '#(([-]?[0-9]+(\.[0-9])?)+(,[0-9]+)?)#', $pString, $pregResultA );
	}
	preg_match ( '#(([-]?[0-9]+(,[0-9])?)+(\.[0-9]+)?)#', $pString, $pregResultB );
	if ((isset ( $pregResultA [0] ) && (! isset ( $pregResultB [0] ) || strstr ( $preResultA [0], $pregResultB [0] ) == 0 || ! $pointset))) {
		$numberString = $pregResultA [0];
		$numberString = str_replace ( '.', '', $numberString );
		$numberString = str_replace ( ',', '.', $numberString );
	} elseif (isset ( $pregResultB [0] ) && (! isset ( $pregResultA [0] ) || strstr ( $pregResultB [0], $preResultA [0] ) == 0 || ! $commaset)) {
		$numberString = $pregResultB [0];
		$numberString = str_replace ( ',', '', $numberString );
	} else {
		return false;
	}
	$result = ( float ) $numberString;
	return $result;
}

if (! function_exists ( 'file_extension' )) {
	function file_extension($filename) {
		return end ( explode ( ".", $filename ) );
	}
}

function float123($str, $set = FALSE) {
	if (preg_match ( "/([0-9\.,-]+)/", $str, $match )) {
		// Found number in $str, so set $str that number
		$str = $match [0];
		
		if (strstr ( $str, ',' )) {
			// A comma exists, that makes it easy, cos we assume it separates the decimal part.
			$str = str_replace ( '.', '', $str ); // Erase thousand seps
			$str = str_replace ( ',', '.', $str ); // Convert , to . for floatval command
			

			return floatval ( $str );
		} else {
			// No comma exists, so we have to decide, how a single dot shall be treated
			if (preg_match ( "/^[0-9]*[\.]{1}[0-9-]+$/", $str ) == TRUE && $set ['single_dot_as_decimal'] == TRUE) {
				// Treat single dot as decimal separator
				return floatval ( $str );
			
			} else {
				// Else, treat all dots as thousand seps
				$str = str_replace ( '.', '', $str ); // Erase thousand seps
				return floatval ( $str );
			}
		}
	} 

	else {
		// No number found, return zero
		return 0;
	}
}

function xml2array($fname) {
	$sxi = new SimpleXmlIterator ( $fname );
	return sxiToArray ( $sxi );
}

function sxiToArray($sxi) {
	$a = array ();
	for($sxi->rewind (); $sxi->valid (); $sxi->next ()) {
		if (! array_key_exists ( $sxi->key (), $a )) {
			$a [$sxi->key ()] = array ();
		}
		if ($sxi->hasChildren ()) {
			$a [$sxi->key ()] [] = sxiToArray ( $sxi->current () );
		} else {
			$a [$sxi->key ()] [] = strval ( $sxi->current () );
		}
	}
	return $a;
}

function makeThisXMLtoArray(DOMNode $oDomNode = null) {
	// return empty array if dom is blank
	if (is_null ( $oDomNode ) && ! $this->hasChildNodes ()) {
		return array ();
	}
	$oDomNode = (is_null ( $oDomNode )) ? $this->documentElement : $oDomNode;
	$arResult = array ();
	if (! $oDomNode->hasChildNodes ()) {
		$arResult [$oDomNode->nodeName] = $oDomNode->nodeValue;
	} else {
		foreach ( $oDomNode->childNodes as $oChildNode ) {
			// how many of these child nodes do we have?
			$oChildNodeList = $oDomNode->getElementsByTagName ( $oChildNode->nodeName ); // count = 0
			$iChildCount = 0;
			// there are x number of childs in this node that have the same tag name
			// however, we are only interested in the # of siblings with the same tag name
			foreach ( $oChildNodeList as $oNode ) {
				if ($oNode->parentNode->isSameNode ( $oChildNode->parentNode )) {
					$iChildCount ++;
				}
			}
			$mValue = makeThisXMLtoArray ( $oChildNode );
			$mValue = is_array ( $mValue ) ? $mValue [$oChildNode->nodeName] : $mValue;
			$sKey = ($oChildNode->nodeName {0} == '#') ? 0 : $oChildNode->nodeName;
			// this will give us a clue as to what the result structure should be
			// how many of thse child nodes do we have?
			

			if ($iChildCount == 1) { // only one child � make associative array
				$arResult [$sKey] = $mValue;
			} elseif ($iChildCount > 1) { // more than one child like this � make numeric array
				$arResult [$sKey] [] = $mValue;
			} elseif ($iChildCount == 0) { // no child records found, this is DOMText or DOMCDataSection
				$arResult [$sKey] = $mValue;
			}
		}
		// if the child is bar, the result will be array(bar)
		// make the result just �bar�
		if (count ( $arResult ) == 1 && isset ( $arResult [0] ) && ! is_array ( $arResult [0] )) {
			$arResult = $arResult [0];
		}
		$arResult = array ($oDomNode->nodeName => $arResult );
	}
	// get our attributes if we have any
	if ($oDomNode->hasAttributes ()) {
		foreach ( $oDomNode->attributes as $sAttrName => $oAttrNode ) {
			// retain namespace prefixes
			$arResult ["@{$oAttrNode->nodeName}"] = $oAttrNode->nodeValue;
		}
	}
	return $arResult;
}
function codeClean($var) {
	if (is_array ( $var )) {
		foreach ( $var as $key => $val ) {
			$output [$key] = codeClean ( $val );
		}
	} else {
		$var = html_entity_decode ( $var );
		$var = strip_tags ( trim ( $var ) );
		if (function_exists ( "mysql_real_escape_string" )) {
			$output = mysql_real_escape_string ( $var );
		} else {
			$output = stripslashes ( $var );
		}
	}
	if (! empty ( $output ))
		return $output;
}

function remove_html_tags($text) {
	//Does what it says on the tin
	///Remove HTML tags
	$text = preg_replace ( array (// Remove invisible content
'@<head[^>]*?>.*?</head>@siu', '@<style[^>]*?>.*?</style>@siu', '@<script[^>]*?.*?</script>@siu', '@<object[^>]*?.*?</object>@siu', '@<embed[^>]*?.*?</embed>@siu', '@<applet[^>]*?.*?</applet>@siu', '@<noframes[^>]*?.*?</noframes>@siu', '@<noscript[^>]*?.*?</noscript>@siu', '@<noembed[^>]*?.*?</noembed>@siu', // Add line breaks before & after blocks
'@<((br)|(hr))@iu', '@</?((address)|(blockquote)|(center)|(del))@iu', '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu', '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu', '@</?((table)|(th)|(td)|(caption))@iu', '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu', '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu', '@</?((frameset)|(frame)|(iframe))@iu' ), array (' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0" ), $text );
	// Remove all remaining tags and comments and return.
	$text = strip_tags ( $text );
	//Ok, so strip_tags sort of does this, but fails to remove script, style etc etc.
	return $text;

}

/*
// "jpg|png|gif" matches all files with these extensions
print random_file_from_dir('test_images/','jpg|png|gif');
// returns test_07.gif

// ".*" matches all extensions (all files)
print random_file_from_dir('test_files/','.*');
// returns foobar_1.zip

// "[0-9]+" matches all extensions that just
// contain numbers (like backup.1, backup.2)
print random_file_from_dir('test_files/','[0-9]+');
// returns backup.7
 * */
function random_file_from_dir($folder = '', $extensions = '.*') {
	
	// fix path:
	$folder = trim ( $folder );
	$folder = ($folder == '') ? './' : $folder;
	
	// check folder:
	if (! is_dir ( $folder )) {
		die ( 'invalid folder given!' );
	}
	
	// create files array
	$files = array ();
	
	// open directory
	if ($dir = @opendir ( $folder )) {
		
		// go trough all files:
		while ( $file = readdir ( $dir ) ) {
			
			if (! preg_match ( '/^\.+$/', $file ) and preg_match ( '/\.(' . $extensions . ')$/', $file )) {
				
				// feed the array:
				$files [] = $file;
			}
		}
		// close directory
		closedir ( $dir );
	} else {
		die ( 'Could not open the folder "' . $folder . '"' );
	}
	
	if (count ( $files ) == 0) {
		die ( 'No files where found :-(' );
	}
	
	// seed random function:
	mt_srand ( ( double ) microtime () * 1000000 );
	
	// get an random index:
	$rand = mt_rand ( 0, count ( $files ) - 1 );
	
	// check again:
	if (! isset ( $files [$rand] )) {
		die ( 'Array index was not found! very strange!' );
	}
	
	// return the random file:
	return $folder . $files [$rand];

}

/**
 *
 * Convert an object to an array
 *
 * @param    object  $object The object to convert
 * @reeturn      array
 *
 */
function objectToArray($object) {
	if (! is_object ( $object ) && ! is_array ( $object )) {
		return $object;
	}
	if (is_object ( $object )) {
		$object = get_object_vars ( $object );
	}
	return array_map ( 'objectToArray', $object );
}

function p($for_print, $is_die = 0) {
	echo "<pre>";
	var_dump ( $for_print );
	echo "</pre>";
	if ($is_die) {
		die ();
	}

}
function ago($time, $granularity = 2) {
	$date = strtotime ( $time );
	$difference = time () - $date;
	$periods = array ('decade' => 315360000, 'year' => 31536000, 'month' => 2628000, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1 );
	
	foreach ( $periods as $key => $value ) {
		if ($difference >= $value) {
			$time = floor ( $difference / $value );
			$difference %= $value;
			$retval .= ($retval ? ' ' : '') . $time . ' ';
			$retval .= (($time > 1) ? $key . 's' : $key);
			$granularity --;
		}
		if ($granularity == '0') {
			break;
		}
	}
	return '' . $retval . ' ago';
}

function urlencode2($url) {
	// safely cast back already encoded "&" within the query
	$url = str_replace ( "&amp;", "&", $url );
	$phpsep = (strlen ( ini_get ( 'arg_separator.input' ) > 0 )) ? ini_get ( 'arg_separator.output' ) : "&";
	// cut optionally anchor
	$ancpos = strrpos ( $url, "#" );
	$lasteq = strrpos ( $url, "=" );
	$lastsep = strrpos ( $url, "&" );
	$lastqsep = strrpos ( $url, "?" );
	$firstsep = strpos ( $url, "?" );
	// recognize wrong positioned anchor example.php#anchor?asdasd
	if ($ancpos !== false || ($ancpos > 0 && ($lasteq > 0 && $lasteq < $ancpos) && ($lastsep > 0 && $lastsep < $ancpos) && ($lastqsep > 0 && $lastqsep < $ancpos))) {
		$anc = "#" . urlencode ( substr ( $url, $ancpos + 1 ) );
		$url = substr ( $url, 0, $ancpos );
	} else {
		$anc = "";
	}
	// separate uri and query string
	if ($firstsep == false) {
		$qry = ""; // no query
		$urlenc = $url . $anc; // anchor
	} else {
		$qry = substr ( $url, $firstsep + 1 );
		$vals = explode ( "&", $qry );
		$valsenc = array ();
		foreach ( $vals as $v ) {
			$buf = explode ( "=", $v );
			$buf [0] = urlencode ( $buf [0] );
			$buf [1] = urlencode ( $buf [1] );
			$valsenc [] = implode ( "=", $buf );
		}
		$urlenc = substr ( $url, 0, $firstsep ); // encoded origin uri
		$urlenc .= "?" . implode ( $phpsep, $valsenc ) . // encoded query string
$anc; // anchor
	}
	$urlenc = htmlentities ( $urlenc, ENT_QUOTES );
	return $urlenc;
}
function cache_file_memory_storage($path) {
	
	static $mem = array ();
	$path_md = md5 ( $path );
	if ($mem ["{$path_md}"] != false) {
		return $mem [$path_md];
	}
	
	$cont = @file_get_contents ( $path );
	$mem [$path_md] = $cont;
	return $cont;
}

/*$fullstring = "this is my [tag]dog[/tag]";
$parsed = get_string_between ( $fullstring, "[tag]", "[/tag]" );

echo $parsed; // (result = dog)*/
function get_string_between($string, $start, $end) {
	$string = " " . $string;
	$ini = strpos ( $string, $start );
	if ($ini == 0)
		return "";
	$ini += strlen ( $start );
	$len = strpos ( $string, $end, $ini ) - $ini;
	return substr ( $string, $ini, $len );
}

function cache_get_file($cache_id, $cache_group = 'global') {
	
	$cache_group = str_replace ( '/', DIRECTORY_SEPARATOR, $cache_group );
	return cache_get_dir ( $cache_group ) . DIRECTORY_SEPARATOR . $cache_id . CACHE_FILES_EXTENSION;

}

function cache_clean_group($cache_group = 'global') {
	//$startTime = slog_time ();
	/*$cleanPattern = CACHEDIR . $cache_group . DIRECTORY_SEPARATOR . '*' . CACHE_FILES_EXTENSION;
		
		$cache_group = $cache_group . DIRECTORY_SEPARATOR;
		
		$cache_group = reduce_double_slashes ( $cache_group );
		
		if (substr ( $cache_group, - 1 ) == DIRECTORY_SEPARATOR) {
			
			$cache_group_noslash = substr ( $cache_group, 0, - 1 );
		
		} else {
			
			$cache_group_noslash = ($cache_group);
		
		}
		
		$recycle_bin = CACHEDIR . 'deleted'. DIRECTORY_SEPARATOR;
		
		if (is_dir ( $recycle_bin ) == false) {
			
			mkdir ( $recycle_bin );
		
		}*/
	
	//print 'delete cache:'  .$cache_group;
	$dir = cache_get_dir ( 'global' );
	//$dir_del = cache_get_dir ( 'global', true );
	//var_dump(CACHEDIR . $cache_group);
	if (is_dir ( $dir )) {
		//dirmv ( $dir, $dir_del, $overwrite = true, $funcloc = NULL );
		recursive_remove_directory ( $dir );
	

	}
	
	$dir = cache_get_dir ( $cache_group );
	//$dir_del = cache_get_dir ( $cache_group, true );
	//var_dump(CACHEDIR . $cache_group);
	if (is_dir ( $dir )) {
		//dirmv ( $dir, $dir_del, $overwrite = true, $funcloc = NULL );
		recursive_remove_directory ( $dir );
	}
}

function cache_get_dir($cache_group = 'global', $deleted_cache_dir = false) {
	if (strval ( $cache_group ) != '') {
		$cache_group = str_replace ( '/', DIRECTORY_SEPARATOR, $cache_group );
		
		//we will seperate the dirs by 1000s
		$cache_group_explode = explode ( DIRECTORY_SEPARATOR, $cache_group );
		$cache_group_new = array ();
		foreach ( $cache_group_explode as $item ) {
			if (intval ( $item ) != 0) {
				$item_temp = intval ( $item ) / 1000;
				$item_temp = ceil ( $item_temp );
				$item_temp = $item_temp . '000';
				$cache_group_new [] = $item_temp;
				$cache_group_new [] = $item;
			
			} else {
				
				$cache_group_new [] = $item;
			}
		
		}
		$cache_group = implode ( DIRECTORY_SEPARATOR, $cache_group_new );
		if ($deleted_cache_dir == false) {
			$cacheDir = CACHEDIR . $cache_group;
		} else {
			//$cacheDir = CACHEDIR . 'deleted' . DIRECTORY_SEPARATOR . date ( 'YmdHis' ) . DIRECTORY_SEPARATOR . $cache_group;
			$cacheDir = CACHEDIR . $cache_group;
			
		}
		if (! is_dir ( $cacheDir )) {
			
			mkdir_recursive ( $cacheDir );
		
		}
		
		return $cacheDir;
	} else {
		return $cache_group;
	}
}

/**
 * extract_tags()
 * Extract specific HTML tags and their attributes from a string.
 *
 * You can either specify one tag, an array of tag names, or a regular expression that matches the tag name(s). 
 * If multiple tags are specified you must also set the $selfclosing parameter and it must be the same for 
 * all specified tags (so you can't extract both normal and self-closing tags in one go).
 * 
 * The function returns a numerically indexed array of extracted tags. Each entry is an associative array
 * with these keys :
 * tag_name	- the name of the extracted tag, e.g. "a" or "img".
 * offset		- the numberic offset of the first character of the tag within the HTML source.
 * contents	- the inner HTML of the tag. This is always empty for self-closing tags.
 * attributes	- a name -> value array of the tag's attributes, or an empty array if the tag has none.
 * full_tag	- the entire matched tag, e.g. '<a href="http://example.com">example.com</a>'. This key 
 * will only be present if you set $return_the_entire_tag to true.	   
 *
 * @param string $html The HTML code to search for tags.
 * @param string|array $tag The tag(s) to extract.							 
 * @param bool $selfclosing	Whether the tag is self-closing or not. Setting it to null will force the script to try and make an educated guess. 
 * @param bool $return_the_entire_tag Return the entire matched tag in 'full_tag' key of the results array.  
 * @param string $charset The character set of the HTML code. Defaults to ISO-8859-1.
 *
 * @return array An array of extracted tags, or an empty array if no matching tags were found. 
 */
function extract_tags($html, $tag, $selfclosing = null, $return_the_entire_tag = false, $charset = 'ISO-8859-1') {
	
	if (is_array ( $tag )) {
		$tag = implode ( '|', $tag );
	}
	
	//If the user didn't specify if $tag is a self-closing tag we try to auto-detect it
	//by checking against a list of known self-closing tags.
	$selfclosing_tags = array ('area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta', 'col', 'param' );
	if (is_null ( $selfclosing )) {
		$selfclosing = in_array ( $tag, $selfclosing_tags );
	}
	
	//The regexp is different for normal and self-closing tags because I can't figure out 
	//how to make a sufficiently robust unified one.
	if ($selfclosing) {
		$tag_pattern = '@<(?P<tag>' . $tag . ')			# <tag
			(?P<attributes>\s[^>]+)?		# attributes, if any
			\s*/?>					# /> or just >, being lenient here 
			@xsi';
	} else {
		$tag_pattern = '@<(?P<tag>' . $tag . ')			# <tag
			(?P<attributes>\s[^>]+)?		# attributes, if any
			\s*>					# >
			(?P<contents>.*?)			# tag contents
			</(?P=tag)>				# the closing </tag>
			@xsi';
	}
	
	$attribute_pattern = '@
		(?P<name>\w+)							# attribute name
		\s*=\s*
		(
			(?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)	# a quoted value
			|							# or
			(?P<value_unquoted>[^\s"\']+?)(?:\s+|$)			# an unquoted value (terminated by whitespace or EOF) 
		)
		@xsi';
	
	//Find all tags 
	if (! preg_match_all ( $tag_pattern, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE )) {
		//Return an empty array if we didn't find anything
		return array ();
	}
	
	$tags = array ();
	foreach ( $matches as $match ) {
		
		//Parse tag attributes, if any
		$attributes = array ();
		if (! empty ( $match ['attributes'] [0] )) {
			
			if (preg_match_all ( $attribute_pattern, $match ['attributes'] [0], $attribute_data, PREG_SET_ORDER )) {
				//Turn the attribute data into a name->value array
				foreach ( $attribute_data as $attr ) {
					if (! empty ( $attr ['value_quoted'] )) {
						$value = $attr ['value_quoted'];
					} else if (! empty ( $attr ['value_unquoted'] )) {
						$value = $attr ['value_unquoted'];
					} else {
						$value = '';
					}
					
					//Passing the value through html_entity_decode is handy when you want
					//to extract link URLs or something like that. You might want to remove
					//or modify this call if it doesn't fit your situation.
					$value = html_entity_decode ( $value, ENT_QUOTES, $charset );
					
					$attributes [$attr ['name']] = $value;
				}
			}
		
		}
		
		$tag = array ('tag_name' => $match ['tag'] [0], 'match' => $match, 'offset' => $match [0] [1], 'contents' => ! empty ( $match ['contents'] ) ? $match ['contents'] [0] : '', //empty for self-closing tags
'attributes' => $attributes );
		if ($return_the_entire_tag) {
			$tag ['full_tag'] = $match [0] [0];
		}
		
		$tags [] = $tag;
	}
	
	return $tags;
}
function extact_tag_by_attr($attr, $value, $xml, $tag = null) {
	if (is_null ( $tag ))
		$tag = '\w+';
	else
		$tag = preg_quote ( $tag );
	
	$attr = preg_quote ( $attr );
	$value = preg_quote ( $value );
	
	$tag_regex = "/<(" . $tag . ")[^>]*$attr\s*=\s*" . "(['\"])$value\\2[^>]*>(.*?)<\/\\1>/";
	
	preg_match_all ( $tag_regex, $xml, $matches, PREG_PATTERN_ORDER );
	
	return $matches [3];
}

//$an_array = array('value1','value2'); 
//print wrap_implode("<a href=\"#\">","</a>"," > ", $an_array);
function wrap_implode($before, $after, $glue, $array) {
	$nbItem = count ( $array );
	$i = 1;
	foreach ( $array as $item ) {
		if ($i < $nbItem) {
			$output .= "$before$item$after$glue";
		} else
			$output .= "$before$item$after";
		$i ++;
	}
	return $output;
}

//A function to convert the glob pattern in a case insensitive version:
//SHORT: make glob() case insensitiv.
//sample call:
//$filelist =  glob( '/path/*/'.globistr('*.PHP') ); //all *.php files in subfolders


//sample call:
//$filelist =  glob( dirname(__FILE__).'/'.globistr('*.JPG') );
//$filelist =  array_merge($filelist,glob( dirname(__FILE__).'/'.globistr('*.JPEG') ));
//$filelist =  array_merge($filelist,glob( dirname(__FILE__).'/'.globistr('*.gif') ));


//multibyte sample:
//$test = "asdasd";
//$pattern = globistr($test,'UTF-8');
//RESULT: pattern:[mM]...
function globistr($string = '', $mbEncoding = ''/*optional e.g.'UTF-8'*/){
	//returns a case insensitive Version of the searchPattern for glob();
	// e.g.: globistr('./*.jpg') => './*.[jJ][pP][gG]'
	// e.g.: glob(dirname(__FILE__).'/'.globistr('*.jpg')) => '/.../*.[jJ][pP][gG]'
	

	// known problems: globistr('./[abc].jpg') => FALSE:'./[[aA][bB][cC]].[jJ][pP][gG]' 
	//(Problem: existing Pattern[abc] will be overwritten)
	// known solution: './[abc].'.globistr('jpg') => RIGHT: './[abc].[jJ][pP][gG]' 
	//(Solution: globistr() only caseSensitive Part, not everything)
	$return = "";
	if ($mbEncoding !== '') { //multiByte Version
		$string = mb_convert_case ( $string, MB_CASE_LOWER, $mbEncoding );
	} else { //standard Version (not multiByte,default)
		$string = strtolower ( $string );
	}
	$mystrlen = strlen ( $string );
	for($i = 0; $i < $mystrlen; $i ++) {
		if ($mbEncoding !== '') { //multiByte Version
			$myChar = mb_substr ( $string, $i, 1, $mbEncoding );
			//$myUpperChar = mb_strtoupper($myChar,$mbEncoding);
			$myUpperChar = mb_convert_case ( $myChar, MB_CASE_UPPER, $mbEncoding );
		} else {
			$myChar = substr ( $string, $i, 1 );
			$myUpperChar = strtoupper ( $myChar );
		}
		if ($myUpperChar !== $myChar) { //there is a lower- and upperChar, / Char is case sentitive
			$return .= '[' . $myChar . $myUpperChar . ']'; //adding both Versions : [xX]
		} else { //only one case Version / Char is case insentitive
			$return .= $myChar; //adding '1','.','*',...
		}
	}
	return $return;
}

?>