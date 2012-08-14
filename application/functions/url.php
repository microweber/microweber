<?php
function isAjax() {
	return (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && ($_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

/**
 * Returns the url segments as array;
 *
 * @param int $k
 *        	The position of the segment you are looking for
 * @return array Array of the segments
 */
function url($k = -1) {
	static $u;
	if ($u == false) {
		$u1 = new uri ();
		$u1 = $u1->uri_string ();
		$u = $u ?  : explode ( '/', trim ( preg_replace ( '/([^\w\/])/i', '', current ( explode ( '?', $u1, 2 ) ) ), '/' ) );
	}
	
	return $k != - 1 ? v ( $u [$k] ) : $u;
}

/**
 * Returns the curent url path, does not include the domain name
 *
 * @return string the url string
 */
function url_string() {
	static $u1;
	if ($u1 == false) {
		$u1 = implode ( '/', url () );
	}
	return $u1;
}

/**
 * Returns the curent site url
 *
 * @param string $add_string
 *        	You can pass any string to be appended to the main site url
 * @return string the url string
 * @example site_url('blog');
 */
function site_url($add_string = false) {
	static $u1;
	if ($u1 == false) {
		$pageURL = 'http';
		if (isset ( $_SERVER ["HTTPS"] ) and ($_SERVER ["HTTPS"] == "on")) {
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER ["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
		}
		
		if (isset ( $_SERVER ['SCRIPT_NAME'] )) {
			$d = dirname ( $_SERVER ['SCRIPT_NAME'] );
			$d = trim ( $d, '/' );
		}
		$url_segs = explode ( '/', $pageURL );
		$i = 0;
		$unset = false;
		foreach ( $url_segs as $v ) {
			if ($unset == true) {
				unset ( $url_segs [$i] );
			}
			if ($v == $d) {
				
				$unset = true;
			}
			
			$i ++;
		}
		$url_segs [] = '';
		$u1 = implode ( '/', $url_segs );
	}
	return $u1 . $add_string;
}
function full_url($skip_ajax = false, $skip_param = false) {
	if ($skip_ajax == false) {
		$is_ajax = isAjax ();
		
		if ($is_ajax == false) {
		} else {
			
			if ($_SERVER ['HTTP_REFERER'] != false) {
				return $_SERVER ['HTTP_REFERER'];
			} else {
			}
		}
	}
	$pageURL = 'http';
	
	if (isset ( $_SERVER ["HTTPS"] )) {
		
		if ($_SERVER ["HTTPS"] == "on") {
			
			$pageURL .= "s";
		}
	}
	
	$pageURL .= "://";
	
	if ($_SERVER ["SERVER_PORT"] != "80") {
		
		$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
	} else {
		
		$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
	}
	if ($skip_param != false) {
		$pageURL = url_param_unset ( $skip_param );
	}
	// $pageURL = rtrim('index.php', $pageURL );
	
	return $pageURL;
}
function url_param($param, $param_sub_position = false, $skip_ajax = false) {
	if ($_POST) {
		
		if ($_POST ['search_by_keyword']) {
			
			if ($param == 'keyword') {
				
				return $_POST ['search_by_keyword'];
			}
		}
	}
	
	$url = full_url ( $skip_ajax );
	
	$rem = site_url ();
	
	$url = str_ireplace ( $rem, '', $url );
	
	$url = str_ireplace ( '?', '/', $url );
	$url = str_ireplace ( '=', ':', $url );
	$url = str_ireplace ( '&', '/', $url );
	
	$segs = explode ( '/', $url );
	foreach ( $segs as $segment ) {
		
		$seg1 = explode ( ':', $segment );
		
		// var_dump($seg1);
		if (($seg1 [0]) == ($param)) {
			
			// if (stristr ( $segment, $param . ':' ) == true) {
			if ($param_sub_position == false) {
				
				$the_param = str_ireplace ( $param . ':', '', $segment );
				
				if ($param == 'custom_fields_criteria') {
					
					// $the_param1 = base64_decode ( $the_param );
					
					$the_param1 = decode_var ( $the_param );
					
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
				
				// $param_value = $params_list [$param_sub_position - 1];
				// $param_value = $the_param;
				return $the_param;
			}
		}
	}
}
function url_param_unset($param, $url = false) {
	if ($url == false) {
		$url = url_string ();
	}
	$site = site_url ();
	
	$url = str_ireplace ( $site, '', $url );
	
	$segs = explode ( '/', $url );
	
	$segs_clean = array ();
	
	foreach ( $segs as $segment ) {
		
		$origsegment = ($segment);
		
		$segment = explode ( ':', $segment );
		
		if ($segment [0] == $param) {
			
			// return $segment [1];
		} else {
			
			$segs_clean [] = $origsegment;
		}
	}
	
	$segs_clean = implode ( '/', $segs_clean );
	
	$site = site_url ( $segs_clean );
	return $site;
}
function url_title($text) {
	
	// Swap out Non "Letters" with a -
	$text = preg_replace ( '/[^\\pL\d]+/u', '-', $text );
	
	// Trim out extra -'s
	$text = trim ( $text, '-' );
	
	// Convert letters that we have left to the closest ASCII representation
	if (function_exists ( 'iconv' )) {
		$text = iconv ( 'utf-8', 'us-ascii//TRANSLIT', $text );
	}
	// Make text lowercase
	
	$strtolower = function_exists('mb_strtolower') ? 'mb_strtolower' : 'strtolower';
	$text = $strtolower($text);
	
 	
	// Strip out anything we haven't been able to convert
	$text = preg_replace ( '/[^-\w]+/', '', $text );
	
	return $text;
}

