<?php
namespace MicroweberPackages\Helper;

if (!defined('MW_ROOTPATH')) {
	if (function_exists('base_path')) {
		define('MW_ROOTPATH', base_path() . DIRECTORY_SEPARATOR);
	} else {
		define('MW_ROOTPATH', DIRECTORY_SEPARATOR);
	}
}

use Illuminate\Support\Facades\Log;
use MicroweberPackages\Helper\URLify;

class UrlManager
{
    public $site_url_var;
    public $current_url_var;

    public function site($add_string = false)
    {
        return $this->site_url($add_string);
    }

    public function hostname()
    {
        static $u1;
        if ($u1 == false) {
            $valid_domain = parse_url($this->site_url());
            if (isset($valid_domain['host'])) {
                $host = str_ireplace('www.', null, $valid_domain['host']);
                $u1 = $host;
            }
        }

        if(!$u1){
            $u1 = 'localhost';
        }

        return $u1;
    }

    public function link_to_file($path)
    {
        $path = str_ireplace(MW_ROOTPATH, '', $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);
        $path = str_ireplace(MW_ROOTPATH, '', $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);
        $path = str_ireplace(MW_ROOTPATH, '', $path);
        $path = str_ireplace($this->site_url(), '', $path);

        if (function_exists('base_path')) {
            $replace_file = base_path();
        } else {
            $replace_file = @dirname(dirname(dirname(__FILE__)));
        }

        $path = str_ireplace($replace_file, '', $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);
        $path = ltrim($path, '/');
        $path = ltrim($path, '\\');

        return $this->site_url($path);
    }

    public function set($url = false)
    {
        return $this->site_url_var = ($url);
    }

    public function set_current($url = false)
    {
        return $this->current_url_var = ($url);
    }

    public function to_path($path)
    {
        if (!is_string($path)) {
            return false;
        }

        if (trim($path) == '') {
            return false;
        }

        $path = str_ireplace($this->site_url(), MW_ROOTPATH, $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);
        $path = $this->clean_url_wrappers($path);

        return normalize_path($path, false);
    }

    public function redirect($url)
    {
        if (trim($url) == '') {
            return false;
        }

        $url = str_ireplace('Location:', '', $url);
        $url = trim($url);

        $redirectUrl = site_url();
        $parseUrl = parse_url($url);
        if (isset($parseUrl['host'])) {
            if ($parseUrl['host'] == site_hostname()) {
                $redirectUrl = $url;
            }
        }

        $redirectUrl = str_replace("\r", "", $redirectUrl);
        $redirectUrl = str_replace("\n", "", $redirectUrl);

        $clearInput = new HTMLClean();
        $redirectUrl = $clearInput->clean($redirectUrl);

        if (headers_sent()) {
            echo '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
        } else {
            return \Redirect::to($redirectUrl);
        }
    }

    public function params($skip_ajax = false)
    {
        return $this->param($param = '__MW_GET_ALL_PARAMS__', $skip_ajax);
    }


    public function param($param, $skip_ajax = false, $force_url = false)
    {

        if ($_POST) {
            if (isset($_POST['search_by_keyword'])) {
                if ($param == 'keyword') {
                    return $_POST['search_by_keyword'];
                }
            }
        }
        $url = $this->current($skip_ajax);
        if ($force_url != false) {
            $url = $force_url;
        }
        $rem = $this->site_url();
        $url = str_ireplace($rem, '', $url);
        $url = str_ireplace('?', '/', $url);
        $url = str_ireplace('=', ':', $url);
        $url = str_ireplace('&', '/', $url);
        $all_params = array();
        $segs = explode('/', $url);
        foreach ($segs as $segment) {
            $seg1 = explode(':', $segment);
            if ($param == '__MW_GET_ALL_PARAMS__') {
                if (isset($seg1[0]) and isset($seg1[1])) {
                    $all_params[$seg1[0]] = $seg1[1];
                }
            } else {
                $param_sub_position = false;
                if (trim($seg1[0]) == trim($param)) {
                    if ($param_sub_position == false) {
                        $the_param = str_ireplace($param . ':', '', $segment);
                        if ($param == 'custom_fields_criteria') {
                            $the_param1 = $this->app->format->base64_to_array($the_param);

                            return $the_param1;
                        }

                        return $the_param;
                    } else {
                        $the_param = str_ireplace($param . ':', '', $segment);
                        $params_list = explode(',', $the_param);
                        if ($param == 'custom_fields_criteria') {
                            $the_param1 = base64_decode($the_param);
                            $the_param1 = unserialize($the_param1);

                            return $the_param1;
                        }

                        return $the_param;
                    }
                }
            }
        }

        if (empty($all_params)) {
            return false;
        }

        return $all_params;
    }


    public function param_set($param, $value = false, $url = false)
    {
        if ($url == false) {
            $url = $this->string();
        }
        $site = $this->site_url();
        $url = str_ireplace($site, '', $url);
        $segs = explode('/', $url);
        $segs_clean = array();
        $found = false;
        foreach ($segs as $segment) {
            $origsegment = ($segment);
            $segment = explode(':', $segment);
            if ($segment[0] == $param) {
                $segment[1] = $value;

                $origsegment = implode(':', $segment);
                $found = true;
                $segs_clean[] = $origsegment;
            } else {
                $segs_clean[] = $origsegment;
            }
        }

        if ($found == false) {

            $segment = array();
            $segment[] = $param;
            $segment[] = $value;
            $origsegment = implode(':', $segment);
            $segs_clean[] = $origsegment;

        }

        $segs_clean = implode('/', $segs_clean);
        $site = ($segs_clean);

        return $site;
    }

    public function param_unset($param, $url = false)
    {
        if ($url == false) {
            $url = $this->string();
        }
        $site = $this->site_url();
        $url = str_ireplace($site, '', $url);
        $segs = explode('/', $url);
        $segs_clean = array();
        foreach ($segs as $segment) {
            $origsegment = ($segment);
            $segment = explode(':', $segment);
            if ($segment[0] == $param) {
            } else {
                $segs_clean[] = $origsegment;
            }
        }
        $segs_clean = implode('/', $segs_clean);
        $site = ($segs_clean);

        return $site;
    }

    /**
     * Returns the current url path, does not include the domain name.
     *
     * @param bool $skip_ajax If true it will try to get the referring url from ajax request
     *
     * @return string the url string
     */
    public function string($skip_ajax = false)
    {
        if ($skip_ajax == true) {
            $url = $this->current($skip_ajax);
        } else {
            $url = false;
        }

        $u1 = implode('/', $this->segment(-1, $url));
        return $u1;
    }

    /**
     * Returns the current url as a string.
     *
     * @param bool $skip_ajax If true it will try to get the referring url from ajax request
     * @param bool $no_get If true it will remove the params after '?'
     *
     * @return string the url string
     */
    public function current($skip_ajax = false, $no_get = false)
    {
        $u = false;
        if ($skip_ajax == true) {
            $is_ajax = $this->is_ajax();
            if ($is_ajax == true) {
                if (isset($_SERVER['HTTP_REFERER']) and $_SERVER['HTTP_REFERER'] != false) {
                    $u = $_SERVER['HTTP_REFERER'];
                }
            }
        }

        if ($u == false and $this->current_url_var != false) {
            $u = $this->current_url_var;
        }
        if ($u == false) {

            $serverrequri = false;
            if (isset($_SERVER['REQUEST_URI'])) {
                $serverrequri = $_SERVER['REQUEST_URI'];
            } elseif (isset($_SERVER['PHP_SELF'])) {
                $serverrequri = $_SERVER['PHP_SELF'];
            }

            $s = '';
            if (is_https()) {
                $s = 's';
            }

            $protocol = 'http';
            $port = 80;
            if (isset($_SERVER['SERVER_PROTOCOL'])) {
                $protocol = $this->strleft(strtolower($_SERVER['SERVER_PROTOCOL']), '/') . $s;
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
            } else {
                if ($serverrequri) {
                  $u = url()->current() . $serverrequri;
                }
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

    /**
     * Return true if the current request is via ajax.
     *
     * @return true|false
     */
    public function is_ajax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    public function strleft($s1, $s2)
    {
        return substr($s1, 0, strpos($s1, $s2));
    }

    /**
     * Returns single URL segment.
     *
     * @param      $num      The segment number
     * @param bool $page_url If false it will use the current URL
     *
     * @return string|false the url segment or false
     */
    public function segment($num = -1, $page_url = false)
    {
        $u = false;
        if ($page_url == false or $page_url == '') {
            $current_url = $this->current();
        } else {
            $current_url = $page_url;
        }

        $site_url = $this->site_url();
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

    public function site_url($add_string = false)
    {
        return site_url($add_string);
    }

    /**
     * Returns ALL URL segments as array.
     *
     * @param bool $page_url If false it will use the current URL
     *
     * @return array|false the url segments or false
     */
    public function segments($page_url = false)
    {
        return $this->segment($k = -1, $page_url);
    }

    public function slug($text)
    {
        // Swap out Non "Letters" with a -
        $text = str_replace('&quot;', '-', $text);
        $text = str_replace('&#039;', '-', $text);
        $text = preg_replace('/[^\\pL\d]+/u', '-', $text);
        // Trim out extra -'s
        $text = trim($text, '-');
        $text = str_replace('""', '-', $text);
        $text = str_replace("'", '-', $text);

        $text = URLify::filter($text);
        // Strip out anything we haven't been able to convert
        $text = preg_replace('/[^-\w]+/', '', $text);
        $text = str_replace(':', '-', $text);

        return $text;
    }

    public function download($requestUrl, $post_params = false, $save_to_file = false)
    {
        if ($post_params != false and is_array($post_params)) {
            $postdata = http_build_query($post_params);
        } else {
            $postdata = false;
        }
        $ref = site_url();

        $opts = array('http' => array('method' => 'POST', 'header' => 'User-Agent: Microweber/' . MW_VERSION . "\r\n" . 'Content-type: application/x-www-form-urlencoded' . "\r\n" . 'Referer: ' . $ref . "\r\n", 'content' => $postdata));
        $requestUrl = str_replace(' ', '%20', $requestUrl);

        if (function_exists('curl_init')) {
            $ch = curl_init($requestUrl);
            curl_setopt($ch, CURLOPT_COOKIEJAR, mw_cache_path() . 'global/cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEFILE, mw_cache_path() . 'global/cookie.txt');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Microweber ' . MW_VERSION . ';)');
            if ($post_params != false) {
                curl_setopt($ch, CURLOPT_POST, count($post_params));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
            }
            //	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
            //curl_setopt($ch, CURLOPT_TIMEOUT, 400);
            $result = curl_exec($ch);

            curl_close($ch);
        } else {
            $context = stream_context_create($opts);
            $result = file_get_contents($requestUrl, false, $context);
        }

        if ($save_to_file == true) {
            file_put_contents($save_to_file, $result);
        } else {
            return $result;
        }

        return false;
    }

    public function replace_site_url($arr)
    {
        $site = $this->site_url();
        if (is_string($arr)) {
            $ret = str_ireplace($site, '{SITE_URL}', $arr);

            return $ret;
        }
        if (is_array($arr) and !empty($arr)) {
            $ret = array();
            foreach ($arr as $k => $v) {
                if (is_array($v) and !empty($v)) {
                    $v = $this->replace_site_url($v);
                } elseif (is_string($v)) {
                    $v = str_ireplace($site, '{SITE_URL}', $v);
                }
                $ret[$k] = $v;
            }

            return $ret;
        }
        return $arr;
    }

    public $repaced_urls = array();

    public function replace_site_url_back($arr)
    {
        if ($arr == false) {
            return;
        }

        if (is_string($arr)) {
            $site = $this->site_url();
            $ret = str_replace('{SITE_URL}', $site, $arr);

            return $ret;
        }

        if (is_array($arr) and !empty($arr)) {
            $ret = array();
            foreach ($arr as $k => $v) {
                if (is_array($v) ) {
                    $v = $this->replace_site_url_back($v);
                } elseif (is_string($v) and $v !== '0') {
                    $v = $this->replace_site_url_back($v);
                }
                $ret[$k] = $v;
            }

            return $ret;
        }
    }

    public function api_link($str = '')
    {
        $str = ltrim($str, '/');

        return $this->site_url('api/' . $str);
    }


    public function clean_url_wrappers($url_str = '')
    {
        static $wrappers;

        if (!$wrappers) {

            $wrappers = array(
                'file',
                'php',
                'zlib',
                'data',
                'phar',
                'glob',
                'ssh2',
                'rar',
                'expect',
            );
//            if (function_exists('stream_get_wrappers')) {
//            //    $wrappers = stream_get_wrappers();
//               // dd($wrappers);
//            } else {
//
//            }

        }

        if ($wrappers and $url_str) {
            foreach ($wrappers as $item) {
                if(is_string($item)){
              //  if($item != 'http'){
              // dd($url_str);
                    $url_str = str_ireplace($item . '://', '//', $url_str);
                }
              //  }
            }
        }
        return $url_str;
    }
}


