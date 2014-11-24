<?php


class Boo  {
    public $app;
    function __construnt(){
        //getInstance


        $this->app = wb();
    }

    function asaa(){

    }
}

random_color();


class url
{
    private static $site_url_var;

    static function site($add_string = false)
    {
        return self::site_url($add_string);
    }

    static function site_url($add_string = false)
    {
        $site_url = self::site_url_var;
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
            self::$site_url_var = $site_url = implode('/', $url_segs);

        }


        return $site_url . $add_string;
    }

    static function is_ajax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }

    static function current($skip_ajax = false, $no_get = false)
    {
        $u = false;
        if ($skip_ajax == true) {
            $is_ajax = self::is_ajax();

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
            if (isset($_SERVER["SERVER_PROTOCOL"])) {
                $protocol = self::strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
            }
            if (isset($_SERVER["SERVER_PORT"])) {
                $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
            }
            if (isset($_SERVER["SERVER_PORT"])) {
                $u = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $serverrequri;
            } elseif (isset($_SERVER["HOSTNAME"])) {
                $u = $protocol . "://" . $_SERVER['HOSTNAME'] . $port . $serverrequri;
            }

        }

        if ($no_get == true) {

            $u = strtok($u, '?');
        }

        return $u;
    }


    static function segment($k = -1, $page_url = false)
    {
        //static $u;
        $u = false;
        if ($page_url == false or $page_url == '') {
            $u1 = self::current();
        } else {

            $u1 = $page_url;
        }

        //if ($u == false) {

        $u2 = self::site_url();


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


        return $k != -1 ? v($u[$k]) : $u;

    }

    static function hostname()
    {
        static $u1;
        if ($u1 == false) {
            $valid_domain = parse_url(self::site_url());
            if (isset($valid_domain['host'])) {
                $host = str_ireplace('www.', null, $valid_domain['host']);
                $u1 = $host;
            }
        }
        return $u1;
    }


    /**
     * Returns the curent url path, does not include the domain name
     *
     * @return string the url string
     */
    function string($skip_ajax = false)
    {
        if ($skip_ajax == true) {
            $url = self::current($skip_ajax);
        } else {
            $url = false;
        }
        //static $u1;
        //if ($u1 == false) {
        $u1 = implode('/', self::segment(-1, $url));
        //}
        return $u1;
    }

    function link_to_file($path)
    {
        // var_dump($path);
        $path = str_ireplace(MW_ROOTPATH, '', $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);
        //var_dump($path);
        return self::site_url($path);
    }

    function to_path($path)
    {
        if (trim($path) == '') {
            return false;
        }

        $path = str_ireplace(self::site_url(), MW_ROOTPATH, $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);

        return normalize_path($path, false);
    }


    function redirect($url)
    {
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

    static function  strleft($s1, $s2)
    {
        return substr($s1, 0, strpos($s1, $s2));
    }


    static function params($skip_ajax = false)
    {
        return self::param($param = "__MW_GET_ALL_PARAMS__", $skip_ajax);
    }

    static function param($param, $skip_ajax = false)
    {
        if ($_POST) {

            if (isset($_POST['search_by_keyword'])) {

                if ($param == 'keyword') {

                    return $_POST['search_by_keyword'];
                }
            }
        }

        $url = self::current($skip_ajax);

        $rem = self::site_url();

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
                         return $the_param;
                    } else {

                        $the_param = str_ireplace($param . ':', '', $segment);

                        $params_list = explode(',', $the_param);

                        if ($param == 'custom_fields_criteria') {

                            $the_param1 = base64_decode($the_param);

                            $the_param1 = unserialize($the_param1);

                            return $the_param1;
                        }

                        // $param_value = $params_list [$param_sub_position - 1];
                        // $param_value = $the_param;
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


    function param_unset($param, $url = false)
    {
        if ($url == false) {
            $url = self::string();
        }
        $site = self::site_url();

        $url = str_ireplace($site, '', $url);

        $segs = explode('/', $url);

        $segs_clean = array();

        foreach ($segs as $segment) {

            $origsegment = ($segment);

            $segment = explode(':', $segment);

            if ($segment[0] == $param) {

                // return $segment [1];
            } else {

                $segs_clean[] = $origsegment;
            }
        }

        $segs_clean = implode('/', $segs_clean);

        $site = ($segs_clean);
        return $site;
    }


    function slug($text)
    {

        // Swap out Non "Letters" with a -
        $text = preg_replace('/[^\\pL\d]+/u', '-', $text);

        // Trim out extra -'s
        $text = trim($text, '-');


        $text = URLify::filter($text);


        // Strip out anything we haven't been able to convert
        $text = preg_replace('/[^-\w]+/', '', $text);

        $text = str_replace(':', '-', $text);

        return $text;
    }


    static function download($requestUrl, $post_params = false, $save_to_file = false)
    {

        if ($post_params == false) {
            //..$post_params = array('date' => date("YmdHis"));
        }
        if ($post_params != false and is_array($post_params)) {
            $postdata = http_build_query($post_params);
        } else {
            $postdata = false;

        }
        $ref = site_url();

        $opts = array('http' => array('method' => 'POST', 'header' => "User-Agent: Microweber/" . MW_VERSION . "\r\n" . 'Content-type: application/x-www-form-urlencoded' . "\r\n" . 'Referer: ' . $ref . "\r\n", 'content' => $postdata));
        $requestUrl = str_replace(' ', '%20', $requestUrl);

        if (function_exists('curl_init')) {
            $ch = curl_init($requestUrl);

            curl_setopt($ch, CURLOPT_COOKIEJAR, MW_CACHE_DIR . "global/cookie.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, MW_CACHE_DIR . "global/cookie.txt");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Microweber " . MW_VERSION . ";)");

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
            //  d($result);
            file_put_contents($save_to_file, $result);
        } else {
            return $result;
        }
        return false;
    }

    static function replace_site_url($arr)
    {
        $site = self::site_url();

        if (is_string($arr)) {

            $ret = str_ireplace($site, '{SITE_URL}', $arr);

            return $ret;
        }

        if (is_array($arr) and !empty($arr)) {

            $ret = array();

            foreach ($arr as $k => $v) {

                if (is_array($v)) {

                    $v = self::replace_site_url($v);
                } else {

                    $v = str_ireplace($site, '{SITE_URL}', $v);

                    // $v = addslashes ( $v );
                    // $v = htmlspecialchars ( $v, ENT_QUOTES, 'UTF-8' );
                }

                $ret[$k] = ($v);
            }

            return $ret;
        }
    }


    static function replace_site_url_back($arr)
    {

        if (is_string($arr)) {

            $parser_mem_crc = 'replace_site_vars_back_' . crc32($arr);

            $ch = mw_var($parser_mem_crc);
            if ($ch != false) {

                $ret = $ch;
            } else {

                $site = self::site_url();

                $ret = str_replace('{SITE_URL}', $site, $arr);
                mw_var($parser_mem_crc, $ret);
            }
            return $ret;
        }

        if (is_array($arr) and !empty($arr)) {

            $ret = array();

            foreach ($arr as $k => $v) {

                if (is_array($v)) {

                    $v = self::replace_site_url_back($v);
                } else if (is_string($v)) {
                    $v = self::replace_site_url_back($v);
                }

                $ret[$k] = ($v);
            }

            return $ret;
        }
    }


    static function api_link($str = '')
    {
        $str = ltrim($str, '/');
        return self::site_url('api/' . $str);
    }

}


/**
 * A PHP port of URLify.js from the Django project
 * (https://github.com/django/django/blob/master/django/contrib/admin/static/admin/js/urlify.js).
 * Handles symbols from Latin languages, Greek, Turkish, Russian, Ukrainian,
 * Czech, Polish, and Latvian. Symbols it cannot transliterate
 * it will simply omit.
 *
 * Usage:
 *
 * echo URLify::filter (' J\'étudie le français ');
 * // "jetudie-le-francais"
 *
 * echo URLify::filter ('Lo siento, no hablo español.');
 * // "lo-siento-no-hablo-espanol"
 */
class URLify
{

    public static $maps = array('latin_map' => array('À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 'ß' => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 'ÿ' => 'y'), 'latin_symbols_map' => array('©' => '(c)'), 'greek_map' => array('α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8', 'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p', 'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w', 'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's', 'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i', 'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8', 'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P', 'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W', 'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I', 'Ϋ' => 'Y'), 'turkish_map' => array('ş' => 's', 'Ş' => 'S', 'ı' => 'i', 'İ' => 'I', 'ç' => 'c', 'Ç' => 'C', 'ü' => 'u', 'Ü' => 'U', 'ö' => 'o', 'Ö' => 'O', 'ğ' => 'g', 'Ğ' => 'G'), 'russian_map' => array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'), 'ukrainian_map' => array('Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G', 'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g'), 'czech_map' => array('č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u', 'ž' => 'z', 'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 'Ž' => 'Z'), 'polish_map' => array('ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z', 'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S', 'Ź' => 'Z', 'Ż' => 'Z'), 'latvian_map' => array('ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n', 'š' => 's', 'ū' => 'u', 'ž' => 'z', 'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z'));

    /**
     * List of words to remove from URLs.
     */
    public static $remove_list = array();

    /**
     * The character map.
     */
    private static $map = array();

    /**
     * The character list as a string.
     */
    private static $chars = '';

    /**
     * The character list as a regular expression.
     */
    private static $regex = '';

    /**
     * Initializes the character map.
     */
    private static function init()
    {
        if (count(self::$map) > 0) {
            return;
        }

        foreach (self::$maps as $map) {
            foreach ($map as $orig => $conv) {
                self::$map[$orig] = $conv;
                self::$chars .= $orig;
            }
        }

        self::$regex = '/[' . self::$chars . ']/u';
    }

    /**
     * Add new characters to the list.
     * `$map` should be a hash.
     */
    public static function add_chars($map)
    {
        if (!is_array($map)) {
            throw new LogicException('$map must be an associative array.');
        }
        self::$maps[] = $map;
        self::$map = array();
        self::$chars = '';
    }

    /**
     * Append words to the remove list.
     * Accepts either single words
     * or an array of words.
     */
    public static function remove_words($words)
    {
        $words = is_array($words) ? $words : array($words);
        self::$remove_list = array_merge(self::$remove_list, $words);
    }

    /**
     * Transliterates characters to their ASCII equivalents.
     */
    public static function downcode($text)
    {
        self::init();

        if (preg_match_all(self::$regex, $text, $matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $char = $matches[0][$i];
                if (isset(self::$map[$char])) {
                    $text = str_replace($char, self::$map[$char], $text);
                }
            }
        }
        return $text;
    }

    /**
     * Filters a string, e.g., "Petty theft" to "petty-theft"
     */
    public static function filter($text, $length = 60)
    {
        $text = self::downcode($text);

        // remove all these words from the string before urlifying
        $text = preg_replace('/\b(' . join('|', self::$remove_list) . ')\b/i', '', $text);

        // if downcode doesn't hit, the char will be stripped here
        $text = preg_replace('/[^-\w\s]/', '', $text);
        // remove unneeded chars
        $text = preg_replace('/^\s+|\s+$/', '', $text);
        // trim
        // leading/trailing
        // spaces
        $text = preg_replace('/[-\s]+/', '-', $text);
        // convert spaces to
        // hyphens
        $text = strtolower($text);
        // convert to lowercase
        return trim(substr($text, 0, $length), '-');
        // trim to first
        // $length
        // chars
    }

    /**
     * Alias of `URLify::downcode()`.
     */
    public static function transliterate($text)
    {
        return self::downcode($text);
    }

}