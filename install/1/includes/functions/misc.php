<?php
/**
 * Create a link by joining the given URL and the parameters given as the second argument.
 * Arguments :  $url - The base url.
 *				$params - An array containing all the parameters and their values.
 *				$use_existing_arguments - Use the parameters that are present in the current page
 * Return : The new url.
 * Example : 
 *			getLink("http://www.google.com/search",array("q"=>"binny","hello"=>"world","results"=>10));
 *					will return
 *			http://www.google.com/search?q=binny&hello=world&results=10
 */
function getLink($url,$params=array(),$use_existing_arguments=false) {
	if($use_existing_arguments) $params = $params + $_GET;
	if(!$params) return $url;
	$link = $url;
	if(strpos($link,'?') === false) $link .= '?'; //If there is no '?' add one at the end
	elseif(!preg_match('/(\?|\&(amp;)?)$/',$link)) $link .= '&amp;'; //If there is no '&' at the END, add one.
	
	$params_arr = array();
	foreach($params as $key=>$value) {
		if(gettype($value) == 'array') { //Handle array data properly
			foreach($value as $val) {
				$params_arr[] = $key . '[]=' . urlencode($val);
			}
		} else {
			$params_arr[] = $key . '=' . urlencode($value);
		}
	}
	$link .= implode('&amp;',$params_arr);
	
	return $link;
}

/**
 * Arguments :  $conditions - An array containing all the validaiton information.
 *				$show(Integer) - The value given here decides how the data should be returned - or printed.[OPTIONAL]
 *						1 = Prints the errors as an HTML List
 *						2 = Return the errors as a string(HTML list)
 *						3 = Return the errors as an array 
 *						4 = Return the errors as an array with field name as the key.
 *						Defaults to 1
 * Super powerful validaiton script for form fields. I may make this a class to do both serverside and 
 *			client side validaiton - both in the same package
 * :TODO: This function is not fully tested. It is not even partaily tested.
 * :TODO: Documentation needed desperatly
 
 The first argument - $conditions is an array with all the validaiton rule
 Each element of the array is one rule.
 Each rule is an associative array. The following keys are supported

 name	: The name of the field that should be checked. ($_REQUEST['date'] - here the name is 'date')
 is		: What sort data should MAKE AN ERROR. If the given type is found as the field value, an error will be raised. Example 'empty'
 title	: The human friendly name for the field (eg. 'Date of Birth')
 error  : The message that should be shown if there is a validation error
 value	: The programmer provided value. Some rules must have an additional value to be matched against. For example the '<' condition must have a value - the user inputed value and the value given in this index will be compared
 when	: This is a method to short-circut the validation. If this is false, or '0' validaiton will NOT take place. The rule will just be ignored.
 
 Example
 $conditions = array(
 	array(
 		'name'	=>	'username',
 		'is'	=>	'empty',
 		'error' =>	'Please provide a valid username'
 	),
 	array(
 		'name'	=>	'username',
 		'is'	=>	'length<',
 		'value'	=> 	3,
 		'error' =>	'Make sure that then username has atleast 3 chars'
 	)
 )
 */
function check($conditions,$show=1) {
	$errors = array();
	$field_errors = array();
	foreach($conditions as $cond) {
		unset($title,$default_error,$error,$when,$input,$is,$value,$name);
		extract($cond);

		if(!isset($title))$title= format($name);
		if(!isset($name)) $name = unformat($title);
		$input = $_REQUEST[$name];
		
		$default_error = t('Error in \'%s\' field!',$title);
		if(!isset($error)) $error = $default_error;
		
		if(isset($when)) {
			if(($when === 0) or ($when === false)) {//Ok - don't validate this field - ignore erros if any
				continue;
			} else if ($when != "") { //When error
				$errors[] = $error;
			}
		}

		switch($is) {
			case 'empty':
				if(!$input) {
					if($error == $default_error) $error = t('The %s is not provided',$title);
					$field_errors[$name][] = $error;
				}
			break;
			case 'not':
				if($error == $default_error) $error = t('The %s should be \'%s\'',$title,$value);
				if($input != $value) $field_errors[$name][] = $error;
			break;
			case 'equal':
				if($error == $default_error) $error = t('The %s should field must not be \'%s\'',$title,$value);
				if($input == $value) $field_errors[$name][] = $error;
			break;
			
			//Numeric Checks			
			case '>':
			case 'greater':
				if($input > $value) $field_errors[$name][] = $error;
			break;
			case '<':
			case 'lesser':
				if($input < $value) $field_errors[$name][] = $error;
			break;
			
			//Length Checks
			case 'length<':
				if(strlen($input) < $value) $field_errors[$name][] = $error;
			break;
			case 'length>':
				if(strlen($input) > $value) $field_errors[$name][] = $error . $value . ' : ' . strlen($input);
			break;

			case 'nan':
			case 'not_number': //Warning: Decimals will get through
				if($input and !is_numeric($input)) {
					$field_errors[$name][] = t('The %s should be a number',$title);
			}
			break;
			
			case 'not_email': //If the field does not match the email regexp, an error is shown
				if(!preg_match('/^[\w\-\.]+\@[\w\-\.]+\.[a-z\.]{2,5}$/',$input)) {
					if($title) $error = t('The %s should be a valid email address',$title);
					else $error = t('Invalid Email address provided');
					$field_errors[$name][] = $error;
				}
				break;
			case 'has_weird': //Check for weird chars
				if(!preg_match('/^[\w\-]*$/',$input)) {
					if($title) $error = t('The %s should not have weird characters',$title);
					else $error = t('Weird characters where found in the input');
					$field_errors[$name][] = $error;
				}
				break;
			case 'not_name': //Check for chars that cannot appear in a title
				if(!preg_match("/^[\w\'\(\)\,\.\/ ]*$/",$input)) {
					if($title) $error = t('The %s has invalid characters',$title);
					else $error = t('Invalid characters where found in the input');
					$field_errors[$name][] = $error;
				}
				break;

			//RegExp
			case 'dont_match':
			case 'not_match':
			case '!match':
				if(!preg_match("/$value/",$input)) $field_errors[$name][] = $error;
			break;
			case 'match':
				if(preg_match("/$value/",$input)) $field_errors[$name][] = $error;
			break;
		}
	}
	
	//Put all errors into one array
	if($field_errors) {
		foreach($field_errors as $name=>$arr) {
			$errors = array_merge($errors,$arr);
		}
		$errors = array_values(array_diff($errors,array('')));
	}
	
	if(!$errors) return '';

	$error_message = "<ul class='validation-errors'>\n<li>";
	$error_message .= implode( "</li>\n<li>",$errors );
	$error_message .= "</li>\n</ul>";
	
	if($show == 1) {//Just show the errors as one list if the user wants it so
		print $error_message;

	} else if($show == 2) { //Return the errors as a string(HTML list)
		return $error_message;

	} else if($show == 3) {//Return the errors as an array
		return $errors;
	
	} else { //Return the errors as a array with field information
		return $field_errors;
	}
}

/**
 * Converts a given PHP array to its eqvalent JSON String
 * Argument : $arr - The PHP array
 * Return : (String) - The JSON String.
 * Link : http://www.bin-co.com/php/scripts/array2json/
 */
function array2json($arr) {
	///:TODO: check for native json_encode function first.
    $parts = array();
    $is_list = false;

    if(!is_array($arr)) return $arr;

    //Find out if the given array is a numerical array
    $keys = array_keys($arr);
    $max_length = count($arr)-1;
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
        $is_list = true;
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
            if($i != $keys[$i]) { //A key fails at position check.
                $is_list = false; //It is an associative array.
                break;
            }
        }
    }

    foreach($arr as $key=>$value) {
        if(is_array($value)) { //Custom handling for arrays
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
        } else {
            $str = '';
            if(!$is_list) $str = '"' . $key . '":';

            //Custom handling for multiple data types
            if(is_numeric($value)) $str .= $value; //Numbers
            elseif($value === false) $str .= 'false'; //The booleans
            elseif($value === true) $str .= 'true';
            else $str .= '"' . addslashes($value) . '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

            $parts[] = $str;
        }
    }
    $json = implode(',',$parts);
    
    if($is_list) return '[' . $json . ']';//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
}

/**
 * A function for easily uploading files. This function will automatically generate a new 
 *        file name so that files are not overwritten.
 * Arguments:     $file_id - The name of the input field contianing the file.
 *                $folder  - The folder to which the file should be uploaded to - it must be writable. OPTIONAL
 *                $types   - A list of comma(,) seperated extensions that can be uploaded. If it is empty, anything goes OPTIONAL
 * Returns  : This is somewhat complicated - this function returns an array with two values...
 *                The first element is randomly generated filename to which the file was uploaded to.
 *                The second element is the status - if the upload failed, it will be 'Error : Cannot upload the file 'name.txt'.' or something like that
 */
function upload($file_id, $folder="", $types="") {
    if(!$_FILES[$file_id]['name']) return array('',t('No file specified'));

    $file_title = $_FILES[$file_id]['name'];
    //Get file extension
    $ext_arr = split("\.",basename($file_title));
    $ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension

    //Not really uniqe - but for all practical reasons, it is
    $uniqer = substr(md5(uniqid(rand(),1)),0,5);
    $file_name = $uniqer . '_' . $file_title;//Get Unique Name

    $all_types = explode(",",strtolower($types));
    if($types) {
        if(in_array($ext,$all_types));
        else {
            $result = t('\'%s\' is not a valid file.',$_FILES[$file_id]['name']); //Show error if any.
            return array('',$result);
        }
    }

    //Where the file must be uploaded to
    if($folder) $folder .= '/';//Add a '/' at the end of the folder
    $uploadfile = $folder . $file_name;

    $result = '';
    //Move the file from the stored location to the new location
    if (!move_uploaded_file($_FILES[$file_id]['tmp_name'], $uploadfile)) {
        $result = t('Cannot upload the file \'%s\'',$_FILES[$file_id]['name']); //Show error if any.
        if(!file_exists($folder)) {
            $result .= t(' : Folder don\'t exist.');
        } elseif(!is_writable($folder)) {
            $result .= t(' : Folder not writable.');
        } elseif(!is_writable($uploadfile)) {
            $result .= t(' : File not writable.');
        }
        $file_name = '';
        
    } else {
        if(!$_FILES[$file_id]['size']) { //Check if the file is made
            @unlink($uploadfile);//Delete the Empty file
            $file_name = '';
            $result = t('Empty file found - please use a valid file.'); //Show the error message
        } else {
            chmod($uploadfile,0777);//Make it universally writable.
        }
    }

    return array($file_name,$result);
}

/**
 * Function  : sendEMail()
 * Agruments : $from - don't make me explain these
 *			  $to
 *			  $message
 *			  $subject 
 * Sends an email with the minimum amount of fuss.
 */
function sendEMail($from_email,$to,$message,$subject) {
	global $Config;
	
	$from_name = $Config['site_title'];
	$site = $Config['site_url'];
	$from_email = $Config['site_email'];
	
	/*Clean The mail of BCC Header Injections before sending the mail*/
	//Code taken from http://in.php.net/manual/en/ref.mail.php#59012

	// Attempt to defend against header injections: 
	$badStrings = array("Content-Type:", 
						"MIME-Version:", 
						"Content-Transfer-Encoding:", 
						"bcc:", 
						"cc:"); 
	
	// Loop through each POST'ed value and test if it contains 
	// one of the $badStrings: 
	foreach($_POST as $k => $v){ 
		foreach($badStrings as $v2){ 
			if(strpos($v, $v2) !== false){ 
				header("HTTP/1.0 403 Forbidden"); 
				exit; 
			} 
		} 
	}     
	/*******************************************************************************/
	$from_str = "$from_name <$from_email>";
	
	if(strpos($message,"<br")===false) { //A Plain Text message
		$type = "text/plain";
	} else { //HTML message
		$type = "text/html";
	}

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: $type; charset=iso-8859-1\r\n";
	$headers .= "From: $from_str";
	
	if(mail($to,$subject,$message,$headers)) return true;
	else return false;
}
 
/**
 * Link: http://www.bin-co.com/php/scripts/load/
 * Version : 1.00.A
 */
function load($url,$options=array('method'=>'get','return_info'=>false)) {
    $url_parts = parse_url($url);
    $info = array(//Currently only supported by curl.
        'http_code'    => 200
    );
    $response = '';
    
    $send_header = array(
        'Accept' => 'text/*',
        'User-Agent' => 'BinGet/1.00.A (http://www.bin-co.com/php/scripts/load/)'
    );

    ///////////////////////////// Curl /////////////////////////////////////
    //If curl is available, use curl to get the data.
    if(function_exists("curl_init") 
                and (!(isset($options['use']) and $options['use'] == 'fsocketopen'))) { //Don't user curl if it is specifically stated to user fsocketopen in the options
        if(isset($options['method']) and $options['method'] == 'post') {
            $page = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
        } else {
            $page = $url;
        }

        $ch = curl_init($url_parts['host']);

        curl_setopt($ch, CURLOPT_URL, $page);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Just return the data - not print the whole thing.
        curl_setopt($ch, CURLOPT_HEADER, true); //We need the headers
        curl_setopt($ch, CURLOPT_NOBODY, false); //The content - if true, will not download the contents
        if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $url_parts['query']);
        }
        //Set the headers our spiders sends
        curl_setopt($ch, CURLOPT_USERAGENT, $send_header['User-Agent']); //The Name of the UserAgent we will be using ;)
        $custom_headers = array("Accept: " . $send_header['Accept'] );
        if(isset($options['modified_since']))
            array_push($custom_headers,"If-Modified-Since: ".gmdate('D, d M Y H:i:s \G\M\T',strtotime($options['modified_since'])));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);

        curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/binget-cookie.txt"); //If ever needed...
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        if(isset($url_parts['user']) and isset($url_parts['pass'])) {
            $custom_headers = array("Authorization: Basic ".base64_encode($url_parts['user'].':'.$url_parts['pass']));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);
        }

        $response = curl_exec($ch);
        $info = curl_getinfo($ch); //Some information on the fetch
        curl_close($ch);

    //////////////////////////////////////////// FSockOpen //////////////////////////////
    } else { //If there is no curl, use fsocketopen
        if(isset($url_parts['query'])) {
            if(isset($options['method']) and $options['method'] == 'post')
                $page = $url_parts['path'];
            else
                $page = $url_parts['path'] . '?' . $url_parts['query'];
        } else {
            $page = $url_parts['path'];
        }

        $fp = fsockopen($url_parts['host'], 80, $errno, $errstr, 30);
        if ($fp) {
            $out = '';
            if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
                $out .= "POST $page HTTP/1.1\r\n";
            } else {
                $out .= "GET $page HTTP/1.0\r\n"; //HTTP/1.0 is much easier to handle than HTTP/1.1
            }
            $out .= "Host: $url_parts[host]\r\n";
            $out .= "Accept: $send_header[Accept]\r\n";
            $out .= "User-Agent: {$send_header['User-Agent']}\r\n";
            if(isset($options['modified_since']))
                $out .= "If-Modified-Since: ".gmdate('D, d M Y H:i:s \G\M\T',strtotime($options['modified_since'])) ."\r\n";

            $out .= "Connection: Close\r\n";
            
            //HTTP Basic Authorization support
            if(isset($url_parts['user']) and isset($url_parts['pass'])) {
                $out .= "Authorization: Basic ".base64_encode($url_parts['user'].':'.$url_parts['pass']) . "\r\n";
            }

            //If the request is post - pass the data in a special way.
            if(isset($options['method']) and $options['method'] == 'post' and $url_parts['query']) {
                $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $out .= 'Content-Length: ' . strlen($url_parts['query']) . "\r\n";
                $out .= "\r\n" . $url_parts['query'];
            }
            $out .= "\r\n";

            fwrite($fp, $out);
            while (!feof($fp)) {
                $response .= fgets($fp, 128);
            }
            fclose($fp);
        }
    }

    //Get the headers in an associative array
    $headers = array();

    if($info['http_code'] == 404) {
        $body = "";
        $headers['Status'] = 404;
    } else {
        //Seperate header and content
        $separator_position = strpos($response,"\r\n\r\n");
        $header_text = substr($response,0,$separator_position);
        $body = substr($response,$separator_position+4);
        
        foreach(explode("\n",$header_text) as $line) {
            $parts = explode(": ",$line);
            if(count($parts) == 2) $headers[$parts[0]] = chop($parts[1]);
        }
    }

    if($options['return_info']) return array('headers' => $headers, 'body' => $body, 'info' => $info);
    return $body;
}

//thanks to soywiz for the following function, posted on http://php.net/fnmatch
//soywiz at php dot net
//17-Jul-2006 10:12
//A better "fnmatch" alternative for windows that converts a fnmatch pattern into a preg one. It should work on PHP >= 4.0.0
if (!function_exists('fnmatch')) {
    function fnmatch($pattern, $string) {
        return @preg_match('/^' . strtr(addcslashes($pattern, '\\.+^$(){}=!<>|'), array('*' => '.*', '?' => '.?')) . '$/i', $string);
    }
}

//safe_glob() by BigueNique at yahoo dot ca
//Function glob() is prohibited on some servers for security reasons as stated on:
//http://seclists.org/fulldisclosure/2005/Sep/0001.html
//(Message "Warning: glob() has been disabled for security reasons in (script) on line (line)")
//safe_glob() intends to replace glob() for simple applications
//using readdir() & fnmatch() instead.
//Since fnmatch() is not available on Windows or other non-POSFIX, I rely
//on soywiz at php dot net fnmatch clone.
//On the final hand, safe_glob() supports basic wildcards on one directory.
//Supported flags: GLOB_MARK. GLOB_NOSORT, GLOB_ONLYDIR
//Return false if path doesn't exist, and an empty array is no file matches the pattern
function safe_glob($pattern, $flags=0) {
    $split=explode('/',$pattern);
    $match=array_pop($split);
    $path=implode('/',$split);
    if (empty($path)) {
	$path=".";
    }
    if (($dir=opendir($path))===false) {
	return false;
    }
    $glob=array();
    while(($file=readdir($dir))!==false) {
        if (!fnmatch($match,$file)) continue;
	if (is_dir("$path/$file")) {
	    if (($file==".") || ($file=="..")) continue;
            if ($flags&GLOB_MARK) $file.=DIRECTORY_SEPARATOR;
            $glob[]=$file;
	} elseif (!($flags&GLOB_ONLYDIR)) {
            $glob[]=$file;
        }
    }
     closedir($dir);
     if (!($flags&GLOB_NOSORT)) sort($glob);
     return $glob;
}


/**
 * This funtion will take a pattern and a folder as the argument and go thru it(recursivly if needed)and return the list of 
 *               all files in that folder.
 * Link			 : http://www.bin-co.com/php/scripts/filesystem/ls/
 * Arguments     :  $pattern - The pattern to look out for [OPTIONAL]
 *					$folder - The path of the directory of which's directory list you want [OPTIONAL]
 *					$recursivly - The funtion will traverse the folder tree recursivly if this is true. Defaults to false. [OPTIONAL]
 *					$options - An array of values 'return_files' or 'return_folders' or both
 * Returns       : A flat list with the path of all the files(no folders) that matches the condition given.
 */
function ls($pattern="*", $folder="", $recursivly=false, $options=array('return_files','return_folders')) {
	if($folder) {
		if(in_array('quiet', $options)) { // If quiet is on, we will suppress the 'no such folder' error
			if(!file_exists($folder)) return array();
		}
		if(!chdir($folder)) return array();
	}
	
	$get_files	= in_array('return_files', $options);
	$get_folders= in_array('return_folders', $options);
	$both = array();
	$folders = array();
	
	// Get the all files and folders in the given directory.
	if($get_files) $both = sfe_glob($pattern, GLOB_BRACE + GLOB_MARK);
	if($recursivly or $get_folders) $folders = safe_glob("*", GLOB_ONLYDIR + GLOB_MARK);
	
	//If a pattern is specified, make sure even the folders match that pattern.
	$matching_folders = array();
	if($pattern !== '*') $matching_folders = safe_glob($pattern, GLOB_ONLYDIR + GLOB_MARK);
	
	//Get just the files by removing the folders from the list of all files.
	$all = array_values(array_diff($both,$folders));
		
	if($recursivly or $get_folders) {
		foreach ($folders as $this_folder) {
			if($get_folders) {
				//If a pattern is specified, make sure even the folders match that pattern.
				if($pattern !== '*') {
					if(in_array($this_folder, $matching_folders)) array_push($all, $this_folder);
				}
				else array_push($all, $this_folder);
			}
			
			if($recursivly) {
				// Continue calling this function for all the folders
				$deep_items = ls($pattern, "$folder/$this_folder", $recursivly, $options); # :RECURSION:
				foreach ($deep_items as $item) {
					array_push($all, $this_folder . $item);
				}
			}
		}
	}
	return $all;
}

