<?php

if (!function_exists('is_https')) {
    function is_https()
    {
        if (isset($_SERVER['HTTPS']) and (strtolower($_SERVER['HTTPS']) == 'on' or $_SERVER['HTTPS'] == '1')) {
            return true;
        } else if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https')) {
            return true;
        } else if (isset($_SERVER['HTTP_X_FORWARDED_SSL']) and (strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on' or $_SERVER['HTTP_X_FORWARDED_SSL'] == '1')) {
            return true;
        } else if (isset($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) and (strtolower($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) == 'https')) {
            return true;
        } else if (isset($_SERVER['HTTP_X_PROTO']) and (strtolower($_SERVER['HTTP_X_PROTO']) == 'ssl')) {
            return true;
        } else if (isset($_SERVER['HTTP_CF_VISITOR']) and strpos($_SERVER["HTTP_CF_VISITOR"], "https")) {
            return true;
        }
        return false;
    }

}

if (!function_exists('site_hostname')) {
    function site_hostname()
    {
        $siteUrl = site_url();
        $parseUrl = parse_url($siteUrl);
        if(!isset($parseUrl['host'])) {
            $parseUrl = parse_url(config('app.url'));
        }
        if(isset($parseUrl['host'])) {
            return $parseUrl['host'];
        }


        return 'localhost';
    }
}

if (!function_exists('site_url')) {
    function site_url($add_string = false)
    {
        static $site_url;

        if (defined('MW_SITE_URL')) {
            $site_url = MW_SITE_URL;
        }


        if ($site_url == false) {
            $pageURL = 'http';
            if (is_https()) {
                $pageURL .= 's';
            }
            $subdir_append = false;
            if (isset($_SERVER['PATH_INFO'])) {
                // $subdir_append = $_SERVER ['PATH_INFO'];
            } elseif (isset($_SERVER['REDIRECT_URL'])) {
                $subdir_append = $_SERVER['REDIRECT_URL'];
            }

            $pageURL .= '://';

            if (isset($_SERVER['HTTP_HOST'])) {
                $pageURL .= $_SERVER['HTTP_HOST'];
            } elseif (isset($_SERVER['SERVER_NAME']) and isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT'] != '80' and $_SERVER['SERVER_PORT'] != '443') {
                $pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
            } elseif (isset($_SERVER['SERVER_NAME'])) {
                $pageURL .= $_SERVER['SERVER_NAME'];
            } elseif (isset($_SERVER['HOSTNAME'])) {
                $pageURL .= $_SERVER['HOSTNAME'];
            }
            $pageURL_host = $pageURL;
            $pageURL .= $subdir_append;
            $d = '';
            if (isset($_SERVER['SCRIPT_NAME'])) {
                $d = dirname($_SERVER['SCRIPT_NAME']);
                $d = trim($d, DIRECTORY_SEPARATOR);
            }

            if (isset($_SERVER['argv']) and isset($_SERVER['argv'][0]) and is_string($_SERVER['argv'][0])) {
                $is_phpunit = $_SERVER['argv'][0];
                if (str_contains($is_phpunit,'phpunit')) {
                    $d = '';
                    $pageURL_host =rtrim( config('app.url'), '/')  ;;
                }
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
                //    $pageURL = str_replace($_SERVER['QUERY_STRING'], '', $pageURL);
            }

            $uz = parse_url($pageURL);
//            if (isset($uz['query'])) {
//                $pageURL = str_replace($uz['query'], '', $pageURL);
//                $pageURL = rtrim($pageURL, '?');
//            }

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

                ++$i;
            }
            $url_segs[] = '';
            $site_url = implode('/', $url_segs);
        }
        if (defined('MW_SITE_URL_PATH_PREFIX')) {
            $site_url .= MW_SITE_URL_PATH_PREFIX;
        }

        if(!$site_url  ){

            //$site_url = 'http://localhost/';
             $site_url = config('app.url');
        }
        return $site_url . $add_string;
    }
}

if (!function_exists('is_ajax')) {
    /**
     * Return true if the current request is via ajax.
     *
     * @return true|false
     */

    function is_ajax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }
}


if (!function_exists('url_current')) {
    /**
     * Returns the current url as a string.
     *
     * @param bool $skip_ajax If true it will try to get the referring url from ajax request
     * @param bool $no_get If true it will remove the params after '?'
     *
     * @return string the url string
     */
    function url_current($skip_ajax = false, $no_get = false)
    {
        $u = false;
        if ($skip_ajax == true) {
            $is_ajax = is_ajax();
            if ($is_ajax == true) {
                if (isset($_SERVER['HTTP_REFERER']) and $_SERVER['HTTP_REFERER'] != false) {
                    $u = $_SERVER['HTTP_REFERER'];
                }
            }
        }


        if ($u == false) {
            if (!isset($_SERVER['REQUEST_URI'])) {
                $serverrequri = $_SERVER['PHP_SELF'];
            } else {
                $serverrequri = $_SERVER['REQUEST_URI'];
            }
            $s = '';
            if (is_https()) {
                $s = 's';
            }

            $protocol = 'http';
            $port = 80;
            if (isset($_SERVER['SERVER_PROTOCOL'])) {
                $protocol = strleft(strtolower($_SERVER['SERVER_PROTOCOL']), '/') . $s;
            }
            if (isset($_SERVER['SERVER_PORT'])) {
                $port = ($_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443') ? '' : (':' . $_SERVER['SERVER_PORT']);
            }

            if (isset($_SERVER['SERVER_PORT']) and isset($_SERVER['HTTP_HOST'])) {
                if (strstr($_SERVER['HTTP_HOST'], ':')) {
                    // port is contained in HTTP_HOST
                    $u = $protocol . '://' . $_SERVER['HTTP_HOST'] . $serverrequri;
                } else {
                    $u = $protocol . '://' . $_SERVER['HTTP_HOST'] . $port . $serverrequri;
                }
            } elseif (isset($_SERVER['HOSTNAME'])) {
                $u = $protocol . '://' . $_SERVER['HOSTNAME'] . $port . $serverrequri;
            }


        }


        if ($no_get == true) {
            $u = strtok($u, '?');
        }
        if (is_string($u)) {
            $u = str_replace(' ', '%20', $u);
        }

        return $u;
    }
}


if (!function_exists('url_segment')) {
    /**
     * Returns single URL segment.
     *
     * @param      $num      The segment number
     * @param bool $page_url If false it will use the current URL
     *
     * @return string|false the url segment or false
     */
    function url_segment($num = -1, $page_url = false)
    {
        $u = false;
        if ($page_url == false or $page_url == '') {
            $current_url = url_current();
        } else {
            $current_url = $page_url;
        }
        $site_url = site_url();
        //  $site_url = rtrim($site_url, '\\');
        // $site_url = rtrim($site_url, '/');
        $site_url = reduce_double_slashes($site_url);
        $site_url = rawurldecode($site_url);

        // $current_url = rtrim($current_url, '\\');
        // $current_url = rtrim($current_url, '/');

        $current_url = rawurldecode($current_url);
        $current_url = str_replace($site_url, '', $current_url);
        $current_url = str_replace(' ', '%20', $current_url);
        $current_url = reduce_double_slashes($current_url);


        if (!isset($u) or $u == false) {
            //   $u = explode('/', mb_trim(preg_replace('/([^\w\:\-\.\%\/])/i', '', current(explode('?', $current_url, 2))), '/'));
            $u = explode('/', current(explode('?', $current_url, 2)));
            if (isset($u[0])) {
                //check for port
                $string = substr($u[0], 0, 1);
                if ($string == ':') {
                    unset($u[0]);
                    $u = array_values($u);
                }
            }
        }

        if ($num != -1) {
            if (isset($u[$num])) {
                return $u[$num];
            } else {
                return;
            }
        } else {
            return $u;
        }
    }

}

if (!function_exists('parse_params')) {
    function parse_params($params)
    {
        $params2 = array();
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
            unset($params2);
        }

        return $params;
    }
}

if (!function_exists('parse_query')) {
    function parse_query($params)
    {
        return \GuzzleHttp\Psr7\Query::parse($params);
    }

}
