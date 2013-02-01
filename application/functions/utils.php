<?php

function reduce_double_slashes($str) {
    return preg_replace("#([^:])//+#", "\\1/", $str);
}

//copy_directory('dirnameSource','dirnameDestination');

function copy_directory($source, $destination) {
    if (is_dir($source)) {
        @mkdir($destination);
        $directory = dir($source);
        while (FALSE !== ( $readdirectory = $directory->read() )) {
            if ($readdirectory == '.' || $readdirectory == '..') {
                continue;
            }
            $PathDir = $source . '/' . $readdirectory;
            if (is_dir($PathDir)) {
                copy_directory($PathDir, $destination . '/' . $readdirectory);
                continue;
            }
            copy($PathDir, $destination . '/' . $readdirectory);
        }

        $directory->close();
    } else {
        copy($source, $destination);
    }
}


// A much better and accurate version can be found
// in Aidan's PHP Repository: 
// http://aidanlister.com/repos/v/function.size_readable.php
 
/**
 * Returns a human readable filesize
 *
 * @author      wesman20 (php.net)
 * @author      Jonas John
 * @version     0.3
 * @link        http://www.jonasjohn.de/snippets/php/readable-filesize.htm
 */
function file_size_nice($size) {
 
    // Adapted from: http://www.php.net/manual/en/function.filesize.php
 
    $mod = 1024;
 
    $units = explode(' ','B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }
 
    return round($size, 2) . ' ' . $units[$i];
}


function character_limiter($str, $length, $minword = 3)
{
    $sub = '';
    $len = 0;
    
    foreach (explode(' ', $str) as $word)
    {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
        
        if (strlen($word) > $minword && strlen($sub) >= $length)
        {
            break;
        }
    }
    
    return $sub . (($len < strlen($str)) ? '...' : '');
}

 

function array_change_key($array, $search, $replace) {

    $arr = array();
    if (isset($array[0]) and is_arr($array[0])) {
        foreach ($array as $item) {
            $item = array_change_key($item, $search, $replace);

            $arr[] = $item;
        }
        return $arr;
    } else {
        if (is_arr($array)) {

            if (isset($array[$search])) {
                $array[$replace] = $array[$search];
            }
            return $array;
        }
    }
    // return TRUE; // Swap complete
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
    is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));
    return is_dir($pathname) || @ mkdir($pathname);
}

function array_rpush($arr, $item) {
    $arr = array_pad($arr, - (count($arr) + 1), $item);
    return $arr;
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
    $path = preg_replace('/[\/\\\]/', $s, $path);
    // $path = preg_replace ( '/' . $s . '$/', '', $path ) . $s;
    $path = str_replace($s . $s, $s, $path);
    if (strval($path) == '') {
        $path = $path_original;
    }
    if ($slash_it == false) {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
    } else {
        $path .= DIRECTORY_SEPARATOR;
        $path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
    }
    if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
        $path = $path_original;
    }
    if ($slash_it == false) {

    } else {
        $path = $path . DIRECTORY_SEPARATOR;
        $path = reduce_double_slashes($path);
        // $path = rtrim ( $path, DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR );
    }
    return $path;
}

function string_nice($var) {
	$var=html_entity_decode($var) ;
   $var=str_replace('-', ' ', $var) ;
      $var=str_replace('_', ' ', $var) ;
   
    
   return $var;
}


function string_clean($var) {
    if (is_array($var)) {
        foreach ($var as $key => $val) {
            $output [$key] = string_clean($val);
        }
    } else {
        $var = html_entity_decode($var);
        $var = strip_tags(trim($var));

        $output = stripslashes($var);
    }
    if (!empty($output))
        return $output;
}

function object_2_array($result) {
    $array = array();
    foreach ($result as $key => $value) {
        if (is_object($value)) {
            $array [$key] = object_2_array($value);
        }
        if (is_array($value)) {
            $array [$key] = object_2_array($value);
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
if (!function_exists('remove_invisible_characters')) {

    function remove_invisible_characters($str, $url_encoded = TRUE) {
        $non_displayables = array();

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
            $str = preg_replace($non_displayables, '', $str, - 1, $count);
        } while ($count);

        return $str;
    }

}

function add_slashes_to_array($arr) {






    if (!empty($arr)) {

        $ret = array();

        foreach ($arr as $k => $v) {

            if (is_array($v)) {

                $v = add_slashes_to_array($v);
            } else {
                // $v =utfString( $v );
                // $v =
                // preg_replace("/[^[:alnum:][:space:][:alpha:][:punct:]]/","",$v);
                // $v = htmlentities ( $v, ENT_NOQUOTES, 'UTF-8' );
                // $v = htmlspecialchars ( $v );
                $v = addslashes($v);
                $v = htmlspecialchars($v);
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
function mw_warning($text, $exit = false) {
	return mw_warn($text, $exit);
}
function mw_warn($text, $exit = false) {
          include (ADMIN_VIEWS_PATH . 'mw_warning.php');
		  if($exit == true){
			die();
		}
}

function mw_notification($text, $exit = false) {
	return mw_notif($text, $exit);
}
function mw_notif($text,$exit = false) {
         include (ADMIN_VIEWS_PATH . 'mw_notification.php');
		if($exit == true){
			die();
		}
}
function json_error($text){
	$arr = array('error' => $text);
	print json_encode($arr);
	exit();
}
function json_html($text){
	$arr = array('html' => $text);
	print json_encode($arr);
	exit();
}
function json_success($text){
	$arr = array('success' => $text);
	print json_encode($arr);
	exit();
}



function mw_error($text, $exit = false) {
          include (ADMIN_VIEWS_PATH . 'mw_error.php');
		if($exit == true){
			die();
		}
}

function debug_info() {
    //if (c('debug_mode')) {

        return include (ADMIN_VIEWS_PATH . 'debug.php');
   // }
}

function remove_slashes_from_array($arr) {


    if (!empty($arr)) {

        $ret = array();

        foreach ($arr as $k => $v) {

            if (is_array($v)) {

                $v = remove_slashes_from_array($v);
            } else {

                $v = htmlspecialchars_decode($v);
                // $v = htmlspecialchars_decode ( $v );
                // $v = html_entity_decode ( $v, ENT_NOQUOTES );
                // $v = html_entity_decode( $v );
                $v = stripslashes($v);
            }

            $ret [$k] = $v;
        }

        return $ret;
    }
}

if (!function_exists('pathToURL')) {

    function pathToURL($path) {
        // var_dump($path);
        $path = str_ireplace(MW_ROOTPATH, '', $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);
        //var_dump($path);
        return site_url($path);
    }

}
if (!function_exists('dir2url')) {

    function dir2url($path) {
        return pathToURL($path);
    }

}
if (!function_exists('dirToURL')) {

    function dirToURL($path) {
        return pathToURL($path);
    }

}


function __encrypt($sData, $sKey='mysecretkey'){ 
    $sResult = ''; 
    for($i=0;$i<strlen($sData);$i++){ 
        $sChar    = substr($sData, $i, 1); 
        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1); 
        $sChar    = chr(ord($sChar) + ord($sKeyChar)); 
        $sResult .= $sChar; 
    } 
    return encode_base64($sResult); 
} 

function __decrypt($sData, $sKey='mysecretkey'){ 
    $sResult = ''; 
    $sData   = decode_base64($sData); 
    for($i=0;$i<strlen($sData);$i++){ 
        $sChar    = substr($sData, $i, 1); 
        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1); 
        $sChar    = chr(ord($sChar) - ord($sKeyChar)); 
        $sResult .= $sChar; 
    } 
    return $sResult; 
} 


function encode_base64($sData){
        $sBase64 = base64_encode($sData);
        return substr(strtr($sBase64, '+/', '-_'), 0, -2);
}

function decode_base64($sData){
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64.'==');
}
 
 
 
    function simple_crypt($key, $string, $action = 'encrypt'){
            $res = '';
            if($action !== 'encrypt'){
                $string = base64_decode($string);
            } 
            for( $i = 0; $i < strlen($string); $i++){
                    $c = ord(substr($string, $i));
                    if($action == 'encrypt'){
                        $c += ord(substr($key, (($i + 1) % strlen($key))));
                        $res .= chr($c & 0xFF);
                    }else{
                        $c -= ord(substr($key, (($i + 1) % strlen($key))));
                        $res .= chr(abs($c) & 0xFF);
                    }
            }
            if($action == 'encrypt'){
                $res = base64_encode($res);
            } 
            return $res;
    }


function encrypt_var($var, $key = false) {
    if ($var == '') {
        return '';
    }
    if ($key == false) {
        $key = md5(dirname(__FILE__));
    }
    $var = encode_var($var);
	
	
	return __encrypt($var, $key);
	
	
	
	
    //  $var = base64_encode($var);
    if (function_exists('mcrypt_encrypt')) {
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $var, MCRYPT_MODE_CBC, md5(md5($key))));
    } else {
        $encrypted = base64_encode($var);
    }

    return $encrypted;
}

function decrypt_var($var, $key = false) {
    if ($var == '') {
        return '';
    }
    if ($key == false) {
        $key = md5(dirname(__FILE__));
    }





$var = __decrypt($var,$key);
 

    try {
        $var =  decode_var($var);
    } catch (Exception $exc) {
        return false;
    }
 
return $var;



    if (function_exists('mcrypt_decrypt')) {
        $var = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($var), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    } else {
        $var = base64_decode($var);
    }



    //  $var = base64_decode($var);
    //$var = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($var), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

    try {
        $var = @unserialize($var);
    } catch (Exception $exc) {
        return false;
    }




    return $var;
}
function str_replace_once($needle , $replace , $haystack){
    // Looks for the first occurence of $needle in $haystack
    // and replaces it with $replace.
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        // Nothing found
    return $haystack;
    }
    return substr_replace($haystack, $replace, $pos, strlen($needle));
}  

function get_file_extension($LoSFileName){
               $LoSFileExtensions = substr($LoSFileName, strrpos($LoSFileName, '.')+1);
                return $LoSFileExtensions;
}  


function no_ext($filename) {

    $filebroken = explode('.', $filename);
     array_pop($filebroken);
    
    return  implode('.', $filebroken);;
}

function encode_var($var) {
    if ($var == '') {
        return '';
    }

    $var = json_encode($var);
    $var = base64_encode($var);
    return $var;
}

function decode_var($var) {
    if ($var == '') {
        return '';
    }
    $var = base64_decode($var);
	
	  try {
        $var = @json_decode($var,1);
    } catch (Exception $exc) {
        return false;
    }
	
	
	
    //$var = unserialize($var);
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
    if (!is_array($Input))
        return trim($Input);
    return array_map('array_trim', $Input);
}

function safe_redirect($url) {
    if (trim($url) == '') {
        return false;
    }
    $url = str_ireplace('Location:', '', $url);
    $url = trim($url);
    if (headers_sent()) {
        print '<meta http-equiv="refresh" content="0;url=' . $url . '">';
    } else {
        header('Location: ' . $url);
    }
    exit();
}

function session_set($name, $val) {
    if (!headers_sent()) {
        if (!isset($_SESSION)) {
            session_start();
        }
        if ($val == false) {
            session_del($name);
        } else {
        	
					 $is_the_same =  session_get($name);
			if($is_the_same != $val){
						
            $_SESSION [$name] = $val;
			} 
        }
    }
}

function session_get($name) {
    if (!headers_sent()) {
        if (!isset($_SESSION)) {
            session_start();
        }
    }
    // d($_SESSION[$name]);
    if (isset($_SESSION[$name])) {
        return $_SESSION [$name];
    } else {
        return false;
    }
}

function session_del($name) {
    if (isset($_SESSION [$name])) {
        unset($_SESSION [$name]);
    }
}

function session_end() {
    $_SESSION = array();
    session_destroy();
}

function recursive_remove_directory($directory, $empty = true) {

    // if the path has a slash at the end we remove it here
    if (substr($directory, - 1) == DIRECTORY_SEPARATOR) {

        $directory = substr($directory, 0, - 1);
    }

    // if the path is not valid or is not a directory ...
    if (!file_exists($directory) || !is_dir($directory)) {

        // ... we return false and exit the function
        return FALSE;

        // ... if the path is not readable
    } elseif (!is_readable($directory)) {

        // ... we return false and exit the function
        return FALSE;

        // ... else if the path is readable
    } else {

        // we open the directory
        $handle = opendir($directory);

        // and scan through the items inside
        while (FALSE !== ($item = readdir($handle))) {

            // if the filepointer is not the current directory
            // or the parent directory
            if ($item != '.' && $item != '..') {

                // we build the new path to delete
                $path = $directory . DIRECTORY_SEPARATOR . $item;

                // if the new path is a directory
                if (is_dir($path)) {
                    // we call this function with the new path
                  recursive_remove_directory($path, $empty);
                    // if the new path is a file
                } else {
                 //   $path = normalize_path($path, false);
                    try {

                        @unlink($path);
                    } catch (Exception $e) {

                    }
                }
            }
        }

        // close the directory
        closedir($handle);

        // if the option to empty is not set to true
        if ($empty == FALSE) {
            @rmdir($directory);
            // try to delete the now empty directory
//            if (!rmdir($directory)) {
//
//                // return false if not possible
//                return FALSE;
//            }
        }

        // return success
        return TRUE;
    }
}

function is_arr($var) {
    return isarr($var);
}

function isarr($var) {
    if (is_array($var) and !empty($var)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 
 * 
 * Recursive glob()
 * 
 * @access public
 * @package utils
 * @subpackage	files
 * @category	files
 * 
 * @version 1.0
 * 
 * 
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
   
     if (!$path && ($dir = dirname($pattern)) != '.') {
	        if ($dir == '\\' || $dir == '/') $dir = '';
	        return rglob(basename($pattern), $flags, $dir . DS);
	    }
	    $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
	    $files = glob($path . $pattern, $flags);
	    foreach ($paths as $p) $files = array_merge($files, rglob($pattern, $flags, $p . DS));
	    return $files;
   
   
    
}

















function directory_tree_grep($q, $path){
		$ret = array();
		$fp = opendir($path);
		while($f = readdir($fp)){
			if( preg_match("#^\.+$#", $f) ) continue; // ignore symbolic links
			$file_full_path = $path.DS.$f;
			 
			if(is_dir($file_full_path)) {
				$ret[]= directory_tree_grep($q, $file_full_path);
			} else if( stristr(file_get_contents($file_full_path), $q) ) {
				$ret[]= $file_full_path;
			}
		}
		return $ret;
	}

/**
   flatten an arbitrarily deep multidimensional array 
   into a list of its scalar values
   (may be inefficient for large structures)
   (will infinite recurse on self-referential structures)
   (could be extended to handle objects)
*/
function array_values_recursive($ary)
{
   $lst = array();
   foreach( array_keys($ary) as $k ){
      $v = $ary[$k];
      if (is_scalar($v)) {
         $lst[] = $v;
      } elseif (is_array($v)) {
         $lst = array_merge( $lst,
            array_values_recursive($v)
         );
      }
   }
   return $lst;
}
function directory_tree($path = '.', $search = false)
	{
	 $return = '';
	 if($search == false){
	    $return = directory_tree_full_path($path);
	 } else {
	 	$res = directory_tree_grep($search, $path);
		 if(!empty($res)){
		 	$res1 = array_values_recursive($res);
		 	foreach ($res1 as $value) {
		 	//	d($value);
				 $return .= directory_tree_full_path($value);
			 }
		 }
	 }
		
		return $return;
	}
 

	function directory_tree_full_path($path = '.')
	{

		$return = '';
		$queue = array();
		
		$is_hidden= substr($path, 0, 2);
	if ($is_hidden == '__') {
		 return $return;
	}
		
		 
			$return .=  "<ul class='directory_tree'>";	
			
			if(is_dir($path)){
		if ($handle = opendir($path)) 
		{
			
		
			while (false !== ($file = readdir($handle))) 
			{
				if (is_dir($path.$file) && $file != '.' && $file !='..')
					$return .= directory_tree_printSubDir($file, $path, $queue);
				else if ($file != '.' && $file !='..')
					$queue[] = $file;
				asort($queue);
			}
			
		$return .=directory_tree_printQueue($queue, $path);
		
		//	 $return = str_replace($path, '', $return);
			 
		}
			} else {
				if(is_file($path)){
				//	$path1 = dirname($path);
					$queue[] = $path;
							$return .=directory_tree_printQueue($queue);
					
				}
			}
		 $return .=  "</ul>";
		
		return $return;
	}
	
	 
	
	
	function directory_tree_printQueue($queue, $path = '')
	{
				$return = '';
			if(empty($queue)){
				
		return $return;
			}
		foreach ($queue as $file) 
		{
			$return .= directory_tree_printFile($file, $path);
		} 
		return $return;
	}
	
	function directory_tree_printFile($file, $path)
	{
			$return = '';
			$file1 = no_ext($file);
			$file1 = ltrim($file1, '_');
		$return .= "<li class='directory_tree_is_file'><a href=\"?file=".$path.$file."\">$file1</a></li>";
		return $return;
	}
	
	function directory_tree_printSubDir($dir, $path)
	{
			$return = '';
		$return .=  "<li class='directory_tree_is_folder'><a href=\"?path=".$path.$dir."\" class=\"toggle\">$dir</a>";
		$return .=  directory_tree_full_path($path.$dir.DS);
		$return .=   "</li>";
		return $return;
	}
	
	 

// ------------------------------------------------------------------------

/**
 * Create a Directory Map
 *
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/directory_helper.html
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE, $full_path = false) {
    if ($fp = @opendir($source_dir)) {
        $filedata = array();
        $new_depth = $directory_depth - 1;
        $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        while (FALSE !== ($file = readdir($fp))) {
            // Remove '.', '..', and hidden files [optional]
            if (!trim($file, '.') OR($hidden == FALSE && $file[0] == '.')) {
                continue;
            }

            if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir . $file)) {
                $filedata[$file] = directory_map($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden,$full_path);
            } else {
            	if($full_path == false){
            		$filedata[] = $file;
				} else {
					$filedata[] = $source_dir.$file;
				}
                
            }
        }

        closedir($fp);
        return $filedata;
    }

    return FALSE;
}

function curencies_list(){
	$row = 1;
	
	$cur_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'libs'.DS.'currencies.csv';
	//d($cur_file);
	if(is_file($cur_file)){
		
if (($handle = fopen($cur_file, "r")) !== FALSE) {
 
 $res = array();
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $itm = array();
	  
	    $num = count($data);
     //   echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
           //echo $data[$c] . "<br />\n";
            $itm[] = $data[$c];
        }
		
		$res[] =$itm;
    }
	
    fclose($handle);
	return $res;
}
	}	
}
 
function percent($num_amount, $num_total) {
    $count1 = $num_amount / $num_total;
    $count2 = $count1 * 100;
    $count = number_format($count2, 0);
    echo $count;
}

function lipsum() {
    return "This is the simple text layout design. You can write what you want directly here, easy as never before. You must know that every layout comes with this default text for your better view of your website. Discover all of cool features of Microweber. You are able to make complex layout designs, connect the layouts with modules and categories with one click, and organize the content as you always wanted. Take look around and play with confidence creating your great website.";
}

api_expose('pixum_img');

