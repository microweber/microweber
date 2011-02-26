<?php
/**
 * Stuff that should have been handled by the language
 */

/**
 * Formats the given text and return the result. For example, if 'avionics_filed_no12' is given,
 *		it will return 'Avionics Filed No 12'.
 * Argument: $value - The string that should be formated
 * Returns : The formated string
 */
function format($value) {
	$value = preg_replace(
		array(	"/_/",				//Changes 'hello_world' to 'hello world'	
				"/([a-zA-Z])(\d)/", //Changes 'no1' to 'no 1'
				"/([a-z])([A-Z])/"	//Changes 'helloWorld' to 'hello World'
		),
		array(" ","$1 $2","$1 $2"),
		$value);
	return ucwords($value);
}

/**
 * Removes all the formating from the given text and returns a string that could be used in an URL. 
 *		This fucntion lowercases the string and replaces all the special chars with '_'
 * Argument: $value - The string that should be un-formated
 * Returns : The unformated string
 */
function unformat($value) {
	$value = preg_replace('/\W/','_',$value);	//Replace all special chars with an '_'
	$value = preg_replace('/__+/','_',$value);	//Replace multiple '_' with a single one.
	$value = preg_replace(
		array('/^_/','/_$/'), //Removes the '_' towards the beginning and the end of the string.
		array('_','_'),
		$value);
	return strtolower($value);
}

/**
 * Takes one or more file names and combines them, using the correct path separator for the 
 * 		current platform and then return the result.
 * Arguments: The parts that make the final path.
 * Example: joinPath('/var','www/html/','try.php'); // returns '/var/www/html/try.php'
 */
function joinPath() {
	$path = '';
	$arguments = func_get_args();
	$args = array();
	foreach($arguments as $a) if($a) $args[] = $a;//Removes the empty elements
	
	$arg_count = count($args);
	for($i=0; $i<$arg_count; $i++) {
		$folder = $args[$i];
		
		if($i != 0 and $folder[0] == DIRECTORY_SEPARATOR) $folder = substr($folder,1); //Remove the first char if it is a '/' - and its not in the first argument
		if($i != $arg_count-1 and substr($folder,-1) == DIRECTORY_SEPARATOR) $folder = substr($folder,0,-1); //Remove the last char - if its not in the last argument
		
		$path .= $folder;
		if($i != $arg_count-1) $path .= DIRECTORY_SEPARATOR; //Add the '/' if its not the last element.
	}
	return $path;
}

/**
 * Same as JoinPath but for external link.
 * Takes one or more file names and combines them, using / 
 * Arguments: The parts that make the final path.
 * Example: joinUrl('/var','www/html/','try.php'); // returns '/var/www/html/try.php'
 */
function joinUrl() {
	$path = '';
	$arguments = func_get_args();
	$args = array();
	foreach($arguments as $a) if($a) $args[] = $a;//Removes the empty elements
	
	$arg_count = count($args);
	for($i=0; $i<$arg_count; $i++) {
		$folder = $args[$i];
		
		if($i != 0 and $folder[0] == '/') $folder = substr($folder,1); //Remove the first char if it is a '/' - and its not in the first argument
		if($i != $arg_count-1 and substr($folder,-1) == '/') $folder = substr($folder,0,-1); //Remove the last char - if its not in the last argument
		
		$path .= $folder;
		if($i != $arg_count-1) $path .= '/'; //Add the '/' if its not the last element.
	}
	return $path;
}

/**
 * A small function to remove an element from a list(numerical array)
 * Arguments: $arr	- The array that should be edited
 *            $value- The value that should be deleted.
 * Returns	: The edited array
 * Link		: http://www.bin-co.com/php/scripts/array_remove.php
 */
function array_remove($arr,$value) {
   return array_values(array_diff($arr,array($value)));
}

/**
 * This function will remove all the specified keys from an array and return the final array.
 * Arguments:	The first argument is the array that should be edited
 *				The arguments after the first argument is a list of keys that must be removed.
 * Example	: array_remove_key($arr,'one','two','three');
 * Return	: The function will return an array after deleting the said keys
 * Link		: http://www.bin-co.com/php/scripts/array_remove.php
 */
function array_remove_key() {
	$args = func_get_args();
	$arr = $args[0];
	$keys = array_slice($args,1);
	
	foreach($arr as $k=>$v) {
		if(in_array($k, $keys))
			unset($arr[$k]);
	}
	return $arr;
}

/**
 * This function will remove all the specified values from an array and return the final array.
 * Arguments:	The first argument is the array that should be edited
 *				The arguments after the first argument is a list of values that must be removed.
 * Example	: array_remove_value($arr,'one','two','three');
 * Return	: The function will return an array after deleting the said values
 * Link		: http://www.bin-co.com/php/scripts/array_remove.php
 */
function array_remove_value() {
	$args = func_get_args();
	$arr = $args[0];
	$values = array_slice($args,1);
	
	foreach($arr as $k=>$v) {
		if(in_array($v, $values))
			unset($arr[$k]);
	}
	return $arr;
}

/**
 * The index function - Created this to avoid the extra isset() check. This will return false 
 *		if the specified index of the specified function is not set. If it there,
 *		this function will return that element.
 * Arguments:	$array - The array in which the item must be checked for
 *				$index - The index to be seached.
 *				$default_value - The value that must be returned if the item is not set
 * Example:
 *	if(i($_REQUEST, 'item')) {
 *		instead of 
 *	if(isset($_REQUEST['item']) and $_REQUEST['item']) {
 */
function i($array, $index=false, $default_value=false) {
	if($index === false) {
		if(isset($array)) return $array;
		return $default_value;
	}
	
	if(!isset($array[$index])) return $default_value;
	
	return $array[$index];
}
