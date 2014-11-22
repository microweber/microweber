<?php

function mw($class = null)
{
    if ($class == null or $class == false or strtolower($class) == 'application') {
        return app();
    } else {
        return app()->make($class);
    }
}

if (!function_exists('d')) {
    function d($dump)
    {
        var_dump($dump);
    }
}


function site_url($add_string = false)
{
    static $site_url;

    if (defined('MW_SITE_URL')) {
        $site_url = MW_SITE_URL;
    }
    if ($site_url == false) {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) and ($_SERVER["HTTPS"] == "on")) {
            $pageURL .= "s";
        }
        $subdir_append = false;
        if (isset($_SERVER['PATH_INFO'])) {
            // $subdir_append = $_SERVER ['PATH_INFO'];
        } elseif (isset($_SERVER['REDIRECT_URL'])) {
            $subdir_append = $_SERVER['REDIRECT_URL'];
        }

        $pageURL .= "://";
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
        if (isset($_SERVER['QUERY_STRING'])) {
            $pageURL = str_replace($_SERVER['QUERY_STRING'], '', $pageURL);
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
        $site_url = implode('/', $url_segs);

    }
    return $site_url . $add_string;
}


/**
 * Converts a path in the appropriate format for win or linux
 *
 * @param string $path
 *            The directory path.
 * @param boolean $slash_it
 *            If true, ads a slash at the end, false by default
 * @return string The formated string
 *
 */
function normalize_path($path, $slash_it = true)
{
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
    }


    return $path;
}


if (!function_exists('reduce_double_slashes')) {
    /**
     * Removes double slashes from sting
     * @param $str
     * @return string
     */
    function reduce_double_slashes($str)
    {
        return preg_replace("#([^:])//+#", "\\1/", $str);
    }
}