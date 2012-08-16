<?php
function reduce_double_slashes($str) {
	return preg_replace ( "#([^:])//+#", "\\1/", $str );
}

/**
 * Makes directory recursive, returns TRUE if exists or made and false on error
 *
 * @param string $pathname
 *        	The directory path.
 * @return boolean returns TRUE if exists or made or FALSE on failure.
 */
function mkdir_recursive($pathname) {
	if ($pathname == '') {
		return false;
	}
	is_dir ( dirname ( $pathname ) ) || mkdir_recursive ( dirname ( $pathname ) );
	return is_dir ( $pathname ) || @ mkdir ( $pathname );
}

/**
 * Converts a path in the appropriate format for win or linux
 *
 * @param string $path
 *        	The directory path.
 * @param boolean $slash_it
 *        	If true, ads a slash at the end, false by default
 * @return string The formated string
 */
function normalize_path($path, $slash_it = true) {
	// DIRECTORY_SEPARATOR is a system variable
	// which contains the right slash for the current
	// system (windows = \ or linux = /)
	$path_original = $path;
	$s = DIRECTORY_SEPARATOR;
	$path = preg_replace ( '/[\/\\\]/', $s, $path );
	// $path = preg_replace ( '/' . $s . '$/', '', $path ) . $s;
	$path = str_replace ( $s . $s, $s, $path );
	if (strval ( $path ) == '') {
		$path = $path_original;
	}
	if ($slash_it == false) {
		$path = rtrim ( $path, DIRECTORY_SEPARATOR );
	} else {
		$path .= DIRECTORY_SEPARATOR;
		$path = rtrim ( $path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR );
	}
	if (strval ( trim ( $path ) ) == '' or strval ( trim ( $path ) ) == '/') {
		$path = $path_original;
	}
	if ($slash_it == false) {
	} else {
		$path = $path . DIRECTORY_SEPARATOR;
		$path = reduce_double_slashes ( $path );
		// $path = rtrim ( $path, DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR );
	}
	return $path;
}
function string_clean($var) {
	if (is_array ( $var )) {
		foreach ( $var as $key => $val ) {
			$output [$key] = string_clean ( $val );
		}
	} else {
		$var = html_entity_decode ( $var );
		$var = strip_tags ( trim ( $var ) );
		if (function_exists ( "mysql_real_escape_string" )) {
			// $output = mysql_real_escape_string ( $var );
		} else {
		}
		$output = stripslashes ( $var );
	}
	if (! empty ( $output ))
		return $output;
}
function object_2_array($result) {
	$array = array ();
	foreach ( $result as $key => $value ) {
		if (is_object ( $value )) {
			$array [$key] = object_2_array ( $value );
		}
		if (is_array ( $value )) {
			$array [$key] = object_2_array ( $value );
		} else {
			$array [$key] = $value;
		}
	}
	return $array;
}

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access public
 * @param
 *        	string
 * @return string
 */
if (! function_exists ( 'remove_invisible_characters' )) {
	function remove_invisible_characters($str, $url_encoded = TRUE) {
		$non_displayables = array ();
		
		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)
		
		if ($url_encoded) {
			$non_displayables [] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12,
			                                        // 14, 15
			$non_displayables [] = '/%1[0-9a-f]/'; // url encoded 16-31
		}
		
		$non_displayables [] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08,
		                                                               // 11, 12,
		                                                               // 14-31,
		                                                               // 127
		
		do {
			$str = preg_replace ( $non_displayables, '', $str, - 1, $count );
		} while ( $count );
		
		return $str;
	}
}
function add_slashes_to_array($arr) {
	if (! empty ( $arr )) {
		
		$ret = array ();
		
		foreach ( $arr as $k => $v ) {
			
			if (is_array ( $v )) {
				
				$v = add_slashes_to_array ( $v );
			} else {
				// $v =utfString( $v );
				// $v =
				// preg_replace("/[^[:alnum:][:space:][:alpha:][:punct:]]/","",$v);
				
				$v = addslashes ( $v );
				// $v = htmlentities ( $v, ENT_NOQUOTES, 'UTF-8' );
				// $v = htmlspecialchars ( $v );
				$v = htmlspecialchars ( $v );
			}
			
			$ret [$k] = ($v);
		}
		
		return $ret;
	}
}
function ago($time, $granularity = 2) {
	$date = strtotime($time);
	$difference = time() - $date;
	$retval = '';
	$periods = array('decade' => 315360000, 'year' => 31536000, 'month' => 2628000, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1);
	foreach ($periods as $key => $value) {
		if ($difference >= $value) {
			$time = floor($difference / $value);
			$difference %= $value;
			$retval .= ($retval ? ' ' : '') . $time . ' ';
			$retval .= (($time > 1) ? $key . 's' : $key);
			$granularity--;
		}
		if ($granularity == '0') {
			break;
		}
	}
	return '' . $retval . ' ago';
}
function debug_info() {
	if (c ( 'debug_mode' )) {
		
		return include (ADMIN_VIEWS_PATH . 'debug.php');
	}
}
function remove_slashes_from_array($arr) {
	if (! empty ( $arr )) {
		
		$ret = array ();
		
		foreach ( $arr as $k => $v ) {
			
			if (is_array ( $v )) {
				
				$v = remove_slashes_from_array ( $v );
			} else {
				
				$v = htmlspecialchars_decode ( $v );
				// $v = htmlspecialchars_decode ( $v );
				// $v = html_entity_decode ( $v, ENT_NOQUOTES );
				// $v = html_entity_decode( $v );
				$v = stripslashes ( $v );
			}
			
			$ret [$k] = $v;
		}
		
		return $ret;
	}
}

if (! function_exists ( 'pathToURL' )) {
	function pathToURL($path) {
		// var_dump($path);
		$path = str_ireplace ( ROOTPATH, '', $path );
		$path = str_ireplace ( '\\', '/', $path );
		// var_dump($path);
		return site_url ( $path );
	}
}
if (! function_exists ( 'dir2url' )) {
	function dir2url($path) {
		return pathToURL ( $path );
	}
}
if (! function_exists ( 'dirToURL' )) {
	function dirToURL($path) {
		return pathToURL ( $path );
	}
}
function encode_var($var) {
	if ($var == '') {
		return '';
	}
	
	$var = serialize ( $var );
	$var = base64_encode ( $var );
	return $var;
}
function decode_var($var) {
	if ($var == '') {
		return '';
	}
	$var = base64_decode ( $var );
	$var = unserialize ( $var );
	return $var;
}

/**
 * Trims a entire array recursivly.
 *
 * @author Jonas John
 * @version 0.2
 * @link http://www.jonasjohn.de/snippets/php/trim-array.htm
 * @param array $Input
 *        	Input array
 */
function array_trim($Input) {
	if (! is_array ( $Input ))
		return trim ( $Input );
	return array_map ( 'array_trim', $Input );
}
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
function session_set($name, $val) {
	if (! isset ( $_SESSION )) {
		session_start ();
	}
	if ($val == false) {
		session_del ( $name );
	} else {
		$_SESSION [$name] = $val;
	}
}
function session_get($name) {
	if (! isset ( $_SESSION )) {
		session_start ();
	}
	
	if (isset ( $_SESSION [$name] )) {
		return $_SESSION [$name];
	} else {
		return false;
	}
}
function session_del($name) {
	if (isset ( $_SESSION [$name] )) {
		unset ( $_SESSION [$name] );
	}
}
function session_end() {
	$_SESSION = array ();
	session_destroy ();
}


function recursive_remove_directory($directory, $empty = FALSE) {

	// if the path has a slash at the end we remove it here
	if (substr ( $directory, - 1 ) == '/') {

		$directory = substr ( $directory, 0, - 1 );

	}

	// if the path is not valid or is not a directory ...
	if (! file_exists ( $directory ) || ! is_dir ( $directory )) {

		// ... we return false and exit the function
		return FALSE;

		// ... if the path is not readable
	} elseif (! is_readable ( $directory )) {

		// ... we return false and exit the function
		return FALSE;

		// ... else if the path is readable
	} else {

		// we open the directory
		$handle = opendir ( $directory );

		// and scan through the items inside
		while ( FALSE !== ($item = readdir ( $handle )) ) {
				
			// if the filepointer is not the current directory
			// or the parent directory
			if ($item != '.' && $item != '..') {

				// we build the new path to delete
				$path = $directory . '/' . $item;

				// if the new path is a directory
				if (is_dir ( $path )) {
						
					// we call this function with the new path
					recursive_remove_directory ( $path );
						
					// if the new path is a file
				} else {
						
					// we remove the file
					@unlink ( $path );

				}
					
			}

		}

		// close the directory
		closedir ( $handle );

		// if the option to empty is not set to true
		if ($empty == FALSE) {
				
			// try to delete the now empty directory
			if (! rmdir ( $directory )) {

				// return false if not possible
				return FALSE;
					
			}

		}

		// return success
		return TRUE;

	}

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
	$paths = glob($path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT);
	$files = glob($path . $pattern, $flags);
	foreach ($paths as $path) {
		$files = array_merge($files, rglob($pattern, $flags, $path));
	}
	return $files;
}