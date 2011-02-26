<?php
/**
 * Transilation function
 * Arguments: $message - First argument is the string to be translated - this will be translated if a corresponding value is present in the locale file.
 *            The rest of the arguments will be passed to an sprintf() with the message - use this to put variables inside the sting.
 * Return: The translated string according to the current locale.
 * Example: t("Thanks for your input");
 *          t("Project '%s' has been deleted", 'Death Ray');
 */
function t() {
	global $config, $locale, $locales;
	
	$arguments = func_get_args();
	$message = $arguments[0];
	$vars = array_splice($arguments, 1); //Gets all the arguments after the 1st argument.
	
	if(isset($locale) and isset($locales[$message])) {
		return call_user_func_array('sprintf', array_merge(array($locales[$message]), $vars));
    
    } else { // The necessary translation was not found.
		if ($config['mode'] == 'd' and $locale != 'en_EN' and $locale) {
			if (isset($locale)) error_log("l10n error: LANG: $locale, message: '$message'");
			else error_log("l10n error: LANG: no locale, message: '$message'");
			
			return call_user_func_array('sprintf', array_merge(array($message . "[translate_me]"), $vars));
		}
		return call_user_func_array('sprintf', array_merge(array($message), $vars));
    }
}


/**
 * Another way of doing the finding the relation between the current file and the document root - but I think findRelation() is a better way(defined in common.php). This function will give us a custom relation based on the contents of $Config global array
 * Return : The relation between the current page and the root of the site. Eg ../..
 */
function getRelation() {
	$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF'];
	$relative_path = str_replace($Config['site_url'],"",$url);
	
	if($url != $relative_path) //If a replace was made...
		$no_of_slashes = substr_count($relative_path ,"/");
	else //If we are still using the absolute url...
		$no_of_slashes = substr_count($relative_path ,"/") - 3;//The - 3 part is for the slashes in 'http://something/'

	$relation = str_repeat("../",$no_of_slashes);
	return $relation;
}

/**
 * This function will escape the user inputed data. It will check to see if magic quotes is on.
 * If it is not on, it will manually escape(using mysql_real_escape_string()) all the requests and return it.
 * Argument : $param_array - [OPTIONAL] The array that must be escaped. If empty, the function uses $_POST + $_GET
 *			  $ignore_magic_quote_setting - [OPTIONAL] If set to true, this will escape the given array no matter what the get_magic_quotes_gpc() returns. Defaults to 'true'
 * Return	: The escaped format of the array.
 * Usage 	: $PARAM = unescapeQuery();
 *			  $QUERY = escapeQuery($PARAM,true);
 */
function escapeQuery($param_array = array(),$ignore_magic_quote_setting = false) {
	$QUERY = array();

	if(!$param_array)
		$param_array = $_POST + $_GET; //Don't use $_REQUEST - it has cookie/session info in it.

	if(!$ignore_magic_quote_setting and get_magic_quotes_gpc()) return $param_array;//If Magic quotes is enabled, just return the data - it is already escaped.

	while(list($key,$value) = each($param_array)) {
		if(is_array($value)) { //Escape Arrays recursively
			$QUERY[$key] = escapeQuery($value,$ignore_magic_quote_setting); //:RECURSION:
		} else {
			if($GLOBALS['sql']) $value = $GLOBALS['sql']->escape($value); //If there is an SQL Connection,
			else $value = addslashes($value);
			
			$QUERY[$key] = htmlspecialchars($value);
		}
	}
	return $QUERY;
}

/**
 * This function will undo the damage made by magic quotes. This will go thru the request array and unescape all the data.
 * Argument : $param_array - [OPTIONAL] The array that must be unescaped. If empty, the function uses $_POST + $_GET
 *			  $ignore_magic_quote_setting - [OPTIONAL] If set to true, this will escape the given array no matter what the get_magic_quotes_gpc() returns. Defaults to 'true'
 * Return	: The proper format of the array - unescaped.
 */
function unescapeQuery($param_array = array(),$ignore_magic_quote_setting = false) {
	$PARAM = array();

	if(!$param_array)
		$param_array = $_POST + $_GET; //Don't use $_REQUEST - it has cookie/session info in it.

	if(!$ignore_magic_quote_setting and !get_magic_quotes_gpc()) return $param_array;//If Magic quotes is disabled, just return the data - it is not escaped.

	while(list($key,$value) = each($param_array)) {
		if(is_array($value)) { //UnEscape Arrays recursively
			$PARAM[$key] = unescapeQuery($value,$ignore_magic_quote_setting); //:RECURSION:
		} else {
			$PARAM[$key] = stripslashes($value);
		}
	}
	return $PARAM;
}

/** 
 * Prints a array, an object or a scalar variable in an easy to view format.
 * Arguments  : $data - the variable that must be displayed
 * Link : http://www.bin-co.com/php/scripts/dump/
 */
function dump($data) {
	if(is_array($data)) { //If the given variable is an array, print using the print_r function.
		print "<pre>-----------------------\n";
		print_r($data);
		print "-----------------------</pre>";
	} elseif (is_object($data)) {
		print "<pre>==========================\n";
		var_export($data);
		print "===========================</pre>";
	} else {
		print "=========&gt;";
		print var_dump($data);
		print "&lt;=========";
	}
}

//http://php.net/autoload
function __autoload($class_name) {
	require_once $class_name . '.php';
}