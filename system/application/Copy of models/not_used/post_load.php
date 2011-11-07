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
$cms_db_tables ['table_menus'] = TABLE_PREFIX . 'menus';
$cms_db_tables ['table_options'] = TABLE_PREFIX . 'options';
$cms_db_tables ['table_media'] = TABLE_PREFIX . 'media';
$cms_db_tables ['table_geodata'] = TABLE_PREFIX . 'geodata';
$cms_db_tables ['table_comments'] = TABLE_PREFIX . 'comments';
$cms_db_tables ['table_votes'] = TABLE_PREFIX . 'votes';
$cms_db_tables ['table_users'] = TABLE_PREFIX . 'users';
$cms_db_tables ['table_users_statuses'] = TABLE_PREFIX . 'users_statuses';
$cms_db_tables ['table_users_notifications'] = TABLE_PREFIX . 'users_notifications';
$cms_db_tables ['table_sessions'] = TABLE_PREFIX . 'sessions';
$cms_db_tables ['table_custom_fields'] = TABLE_PREFIX . 'content_custom_fields';
$cms_db_tables ['table_cart'] = TABLE_PREFIX . 'cart';
$cms_db_tables ['table_cart_orders'] = TABLE_PREFIX . 'cart_orders';
$cms_db_tables ['table_cart_promo_codes'] = TABLE_PREFIX . 'cart_promo_codes';
$cms_db_tables ['table_countries'] = TABLE_PREFIX . 'countries';
$cms_db_tables ['table_cart_orders_shipping_cost'] = TABLE_PREFIX . 'cart_orders_shipping_cost';
$cms_db_tables ['table_cart_currency'] = TABLE_PREFIX . 'cart_currency';

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
function in_multiarray($elem, $array)
    {
        $top = sizeof($array) - 1;
        $bottom = 0;
        while($bottom <= $top)
        {
            if($array[$bottom] == $elem)
                return true;
            else 
                if(is_array($array[$bottom]))
                    if(in_multiarray($elem, ($array[$bottom])))
                        return true;
                    
            $bottom++;
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

    foreach ($needles as $needle) { 

        if ( in_array($needle, $haystack) ) { 
            return true; 
        } 
    } 

    return false; 
} 
    
    
    
function htmlGetTagFromHtmlString( $attr, $value, $xml, $tag=null ) {
  if( is_null($tag) )
    $tag = '\w+';
  else
    $tag = preg_quote($tag);

  $attr = preg_quote($attr);
  $value = preg_quote($value);

  $tag_regex = "/<(".$tag.")[^>]*$attr\s*=\s*".
                "(['\"])$value\\2[^>]*>(.*?)<\/\\1>/";

  preg_match_all($tag_regex,
                 $xml,
                 $matches,
                 PREG_PATTERN_ORDER);

  return $matches[3];
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


// to use this function to totally remove a directory, write:
// recursive_remove_directory('path/to/directory/to/delete');


// to use this function to empty a directory, write:
// recursive_remove_directory('path/to/full_directory',TRUE);
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

function mb_str_ireplace($needle, $replacement, $haystack) {
	
	$needle_len = mb_strlen ( $needle );
	
	$replacement_len = mb_strlen ( $replacement );
	
	if ($haystack == '') {
		
		return false;
	
	}
	
	$pos = mb_stripos ( $haystack, $needle );
	
	while ( $pos !== false ) {
		
		$haystack = mb_substr ( $haystack, 0, $pos ) . $replacement . mb_substr ( $haystack, $pos + $needle_len );
		
		$pos = mb_stripos ( $haystack, $needle, $pos + $replacement_len );
	
	}
	
	return $haystack;

}

/**
 * Makes directory, returns TRUE if exists or made
 *
 * @param string $pathname The directory path.
 * @return boolean returns TRUE if exists or made or FALSE on failure.
 */

function mkdir_recursive($pathname) {
	
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
		
		$pos = stripos ( $subject, $search, $pos );
		
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

function p($for_print, $is_die = 0) {
	echo "<pre>";
	print_r ( $for_print );
	echo "</pre>";
	if ($is_die)
		die ();

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