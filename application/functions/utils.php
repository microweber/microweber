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
function debug_info() {
	if (c ( 'debug_mode' )) {
		
		return include (APPPATH_FULL . 'views' . DS . 'debug.php');
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
