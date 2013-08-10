<?php

defined('T') or die("You cannot call this file on its own. Include index.php first.");

if (!defined('__DIR__')) {
	define('__DIR__', dirname(__FILE__));
}
 

if (version_compare(phpversion(), "5.3.0", "<=")) {
  exit("Error: You must have PHP version 5.3 or greater to run Microweber");
}


/*
* Microweber autoloader
* Loads up classes with namespaces
* Add more dicectories with set_include_path
 */
set_include_path(BASE_DIR . 'classes' . DS . PATH_SEPARATOR .BASE_DIR . 'controllers' . DS . PATH_SEPARATOR .   get_include_path());

function mw_autoload($className) {
	$className = ltrim($className, '\\');
	$fileName = '';
	$namespace = '';

	if ($lastNsPos = strripos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}




	if ($className != '') {

       // set_include_path( MODULES_DIR .strtolower($className). PATH_SEPARATOR . get_include_path());



        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		require $fileName;
	}

}

spl_autoload_register('mw_autoload');
 



function url_segment($k = -1, $page_url = false) {
	//static $u;
	$u = false;
	if ($page_url == false or $page_url == '') {
		$u1 = curent_url();
	} else {

		$u1 = $page_url;
	}

	//if ($u == false) {

	$u2 = site_url();





	$u1 = rtrim($u1, '\\');
	$u1 = rtrim($u1, '/');

	$u2 = rtrim($u2, '\\');
	$u2 = rtrim($u2, '/');
	$u2 = reduce_double_slashes($u2);
	$u1 = reduce_double_slashes($u1);
	$u2 = rawurldecode($u2);
 	$u1 = rawurldecode($u1);
	$u1 = str_replace($u2, '', $u1);

	if (!isset($u) or $u == false) {
		$u = explode('/', trim(preg_replace('/([^\w\:\-\.\%\/])/i', '', current(explode('?', $u1, 2))), '/'));

	}
	//}


	return $k != -1 ? val_or_null($u[$k]) : $u;

}

function val_or_null(&$v, $d = NULL) {
	return isset($v) ? $v : $d;
}


if (!isset($mw_site_url)) {
    $mw_site_url = false;
}
function site_url($add_string = false)
{
    global $mw_site_url;
    if ($mw_site_url == false) {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) and ($_SERVER["HTTPS"] == "on")) {
            $pageURL .= "s";
        }

        $subdir_append = false;
        if (isset($_SERVER['PATH_INFO'])) {
            // $subdir_append = $_SERVER ['PATH_INFO'];
        } elseif (isset($_SERVER['REDIRECT_URL'])) {
            $subdir_append = $_SERVER['REDIRECT_URL'];
        } else {
            //  $subdir_append = $_SERVER ['REQUEST_URI'];
        }

        $pageURL .= "://";
        //error_log(serialize($_SERVER));
        if (isset($_SERVER["SERVER_NAME"]) and isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } elseif (isset($_SERVER["SERVER_NAME"])) {
            $pageURL .= $_SERVER["SERVER_NAME"];
        } else if (isset($_SERVER["HOSTNAME"])) {
            $pageURL .= $_SERVER["HOSTNAME"];
        }
        $pageURL_host = $pageURL;
        $pageURL .= $subdir_append;

        $d = '';
        if (isset($_SERVER['SCRIPT_NAME'])) {
            $d = dirname($_SERVER['SCRIPT_NAME']);
            $d = trim($d, DIRECTORY_SEPARATOR);
        }

        if ($d == '') {
            $pageURL = $pageURL_host;
        } else {

            $pageURL_host = rtrim($pageURL_host, '/') . '/';
            $d = ltrim($d, '/');
            $d = ltrim($d, DIRECTORY_SEPARATOR);

            $pageURL = $pageURL_host . $d;

        }
        //
        if (isset($_SERVER['QUERY_STRING'])) {
            $pageURL = str_replace($_SERVER['QUERY_STRING'], '', $pageURL);
        }

        if (isset($_SERVER['REDIRECT_URL'])) {
            //  $pageURL = str_replace($_SERVER ['REDIRECT_URL'], '', $pageURL);
        }

        $uz = parse_url($pageURL);
        if (isset($uz['query'])) {
            $pageURL = str_replace($uz['query'], '', $pageURL);
            $pageURL = rtrim($pageURL, '?');
        }

        $url_segs = explode('/', $pageURL);

        $i = 0;
        $unset = false;
        foreach ($url_segs as $v) {
            if ($unset == true and $d != '') {

                unset($url_segs[$i]);
            }
            if ($v == $d and $d != '') {

                $unset = true;
            }

            $i++;
        }
        $url_segs[] = '';
        $mw_site_url = implode('/', $url_segs);

    }
	
	 
    return $mw_site_url . $add_string;
}




/**
 * Returns the curent url path, does not include the domain name
 *
 * @return string the url string
 */
function url_string($skip_ajax = false) {
	if ($skip_ajax == true) {
		$url = curent_url($skip_ajax);
	} else {
		$url = false;
	}
	//static $u1;
	//if ($u1 == false) {
	$u1 = implode('/', url_segment(-1, $url));
	//}
	return $u1;
}

function curent_url($skip_ajax = false, $no_get = false) {
	$u = false;
	if ($skip_ajax == true) {
		$is_ajax = isAjax();

		if ($is_ajax == true) {
			if ($_SERVER['HTTP_REFERER'] != false) {
				$u = $_SERVER['HTTP_REFERER'];
			} else {

			}
		}
	}

	if ($u == false) {

		if (!isset($_SERVER['REQUEST_URI'])) {
			$serverrequri = $_SERVER['PHP_SELF'];
		} else {
			$serverrequri = $_SERVER['REQUEST_URI'];
		}

		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		
		$protocol = 'http';
		$port = 80;
		if(isset($_SERVER["SERVER_PROTOCOL"])){
		$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
		}
		if(isset($_SERVER["SERVER_PORT"])){
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
		}
		 if(isset($_SERVER["SERVER_PORT"])){
		$u = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $serverrequri;
		} elseif(isset($_SERVER["HOSTNAME"])){ 
		 $u = $protocol . "://" . $_SERVER['HOSTNAME'] . $port . $serverrequri;
		}
		
	}

	if ($no_get == true) {

		$u = strtok($u, '?');
	}

	return $u;
}

function isAjax() {
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}


function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}


/**
 * Removes double slashes from sting
 * @param $str
 * @return string
 */
function reduce_double_slashes($str)
{
    return preg_replace("#([^:])//+#", "\\1/", $str);
}