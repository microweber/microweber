<?php

function isAjax() {
    return (isset($_SERVER ['HTTP_X_REQUESTED_WITH']) && ($_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

function url_segment($k = -1) {
    static $u;
    if ($u == false) {

        $u1 = curent_url();
        $u2 = site_url();

        $u1 = str_replace($u2, '', $u1);

        $u = $u ? : explode('/', trim(preg_replace('/([^\w\:\-\.\/])/i', '', current(explode('?', $u1, 2))), '/'));

        // $u = $u ? : explode ( '/', trim ( preg_replace (
        // '/^([A-Za-z0-9:]+)/i', '', current ( explode ( '?', $u1, 2 ) ) ), '/'
        // )
        // );
    }

    return $k != - 1 ? v($u [$k]) : $u;
}

/**
 * Returns the url segments as array;
 *
 * @param int $k
 *        	The position of the segment you are looking for
 * @return array Array of the segments
 */
function url($k = -1) {
    static $u;
    if ($u == false) {

        $u1 = curent_url();
        $u2 = site_url();

        $u1 = str_replace($u2, '', $u1);

        $u = $u ? : explode('/', trim(preg_replace('/([^\w\:\-\/])/i', '', current(explode('?', $u1, 2))), '/'));

        // $u = $u ? : explode ( '/', trim ( preg_replace (
        // '/^([A-Za-z0-9:]+)/i', '', current ( explode ( '?', $u1, 2 ) ) ), '/'
        // )
        // );
    }

    return $k != - 1 ? v($u [$k]) : $u;
}

/**
 * Returns the curent url path, does not include the domain name
 *
 * @return string the url string
 */
function url_string() {
    static $u1;
    if ($u1 == false) {
        $u1 = implode('/', url_segment());
    }
    return $u1;
}

function curent_url() {
    if (!isset($_SERVER ['REQUEST_URI'])) {
        $serverrequri = $_SERVER ['PHP_SELF'];
    } else {
        $serverrequri = $_SERVER ['REQUEST_URI'];
    }

    $s = empty($_SERVER ["HTTPS"]) ? '' : ($_SERVER ["HTTPS"] == "on") ? "s" : "";
    $protocol = strleft(strtolower($_SERVER ["SERVER_PROTOCOL"]), "/") . $s;
    $port = ($_SERVER ["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER ["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER ['SERVER_NAME'] . $port . $serverrequri;
}

function strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}

function admin_url($add_string = false) {

    $admin_url = c('admin_url');

    return site_url($admin_url) . '/' . $add_string;
}

/**
 * Returns the curent site url
 *
 * @param string $add_string
 *        	You can pass any string to be appended to the main site url
 * @return string the url string
 * @example site_url('blog');
 */
if (!function_exists('site_url')) {

    function site_url($add_string = false) {
        static $u1;
        if ($u1 == false) {
            $pageURL = 'http';
            if (isset($_SERVER ["HTTPS"]) and ($_SERVER ["HTTPS"] == "on")) {
                $pageURL .= "s";
            }
            $pageURL .= "://";
            if ($_SERVER ["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
            }

            if (isset($_SERVER ['SCRIPT_NAME'])) {
                $d = dirname($_SERVER ['SCRIPT_NAME']);
                $d = trim($d, '/');
            }
            $url_segs = explode('/', $pageURL);
            $i = 0;
            $unset = false;
            foreach ($url_segs as $v) {
                if ($unset == true) {
                    unset($url_segs [$i]);
                }
                if ($v == $d) {

                    $unset = true;
                }

                $i++;
            }
            $url_segs [] = '';
            $u1 = implode('/', $url_segs);
        }
        return $u1 . $add_string;
    }

}

function full_url($skip_ajax = false, $skip_param = false) {
    if ($skip_ajax == false) {
        $is_ajax = isAjax();

        if ($is_ajax == false) {

        } else {

            if ($_SERVER ['HTTP_REFERER'] != false) {
                return $_SERVER ['HTTP_REFERER'];
            } else {

            }
        }
    }
    $pageURL = 'http';

    if (isset($_SERVER ["HTTPS"])) {

        if ($_SERVER ["HTTPS"] == "on") {

            $pageURL .= "s";
        }
    }

    $pageURL .= "://";

    if ($_SERVER ["SERVER_PORT"] != "80") {

        $pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
    } else {

        $pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
    }
    if ($skip_param != false) {
        $pageURL = url_param_unset($skip_param);
    }
    // $pageURL = rtrim('index.php', $pageURL );

    return $pageURL;
}

function url_params($skip_ajax = false) {
    return url_param($param = "__MW_GET_ALL_PARAMS__", $param_sub_position = false, $skip_ajax);
}

function url_param($param, $param_sub_position = false, $skip_ajax = false) {
    if ($_POST) {

        if (isset($_POST ['search_by_keyword'])) {

            if ($param == 'keyword') {

                return $_POST ['search_by_keyword'];
            }
        }
    }

    $url = full_url($skip_ajax);

    $rem = site_url();

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


            // var_dump($seg1);
            if (($seg1 [0]) == ($param)) {

                // if (stristr ( $segment, $param . ':' ) == true) {
                if ($param_sub_position == false) {

                    $the_param = str_ireplace($param . ':', '', $segment);

                    if ($param == 'custom_fields_criteria') {

                        // $the_param1 = base64_decode ( $the_param );

                        $the_param1 = decode_var($the_param);

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

                    // $param_value = $params_list [$param_sub_position - 1];
                    // $param_value = $the_param;
                    return $the_param;
                }
            }
        }
    }
    return $all_params;
}

function url_param_unset($param, $url = false) {
    if ($url == false) {
        $url = url_string();
    }
    $site = site_url();

    $url = str_ireplace($site, '', $url);

    $segs = explode('/', $url);

    $segs_clean = array();

    foreach ($segs as $segment) {

        $origsegment = ($segment);

        $segment = explode(':', $segment);

        if ($segment [0] == $param) {

            // return $segment [1];
        } else {

            $segs_clean [] = $origsegment;
        }
    }

    $segs_clean = implode('/', $segs_clean);

    $site = ($segs_clean);
    return $site;
}

function url_title($text) {

    // Swap out Non "Letters" with a -
    $text = preg_replace('/[^\\pL\d]+/u', '-', $text);

    // Trim out extra -'s
    $text = trim($text, '-');

    // Convert letters that we have left to the closest ASCII representation
    if (function_exists('iconv')) {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
    $text = URLify::filter($text);
    // Make text lowercase

    $strtolower = function_exists('mb_strtolower') ? 'mb_strtolower' : 'strtolower';
    $text = $strtolower($text);

    // Strip out anything we haven't been able to convert
    $text = preg_replace('/[^-\w]+/', '', $text);

    return $text;
}

function replace_site_vars($arr) {
    $site = site_url();

    if (is_string($arr)) {

        $ret = str_ireplace($site, '{SITE_URL}', $arr);

        return $ret;
    }

    if (is_array($arr) and !empty($arr)) {

        $ret = array();

        foreach ($arr as $k => $v) {

            if (is_array($v)) {

                $v = replace_site_vars($v);
            } else {

                $v = str_ireplace($site, '{SITE_URL}', $v);

                // $v = addslashes ( $v );
                // $v = htmlspecialchars ( $v, ENT_QUOTES, 'UTF-8' );
            }

            $ret [$k] = ($v);
        }

        return $ret;
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
class URLify {

    public static $maps = array(
        'latin_map' => array(
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Æ' => 'AE',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ð' => 'D',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ő' => 'O',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ű' => 'U',
            'Ý' => 'Y',
            'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'æ' => 'ae',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'd',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ő' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ű' => 'u',
            'ý' => 'y',
            'þ' => 'th',
            'ÿ' => 'y'
        ),
        'latin_symbols_map' => array(
            '©' => '(c)'
        ),
        'greek_map' => array(
            'α' => 'a',
            'β' => 'b',
            'γ' => 'g',
            'δ' => 'd',
            'ε' => 'e',
            'ζ' => 'z',
            'η' => 'h',
            'θ' => '8',
            'ι' => 'i',
            'κ' => 'k',
            'λ' => 'l',
            'μ' => 'm',
            'ν' => 'n',
            'ξ' => '3',
            'ο' => 'o',
            'π' => 'p',
            'ρ' => 'r',
            'σ' => 's',
            'τ' => 't',
            'υ' => 'y',
            'φ' => 'f',
            'χ' => 'x',
            'ψ' => 'ps',
            'ω' => 'w',
            'ά' => 'a',
            'έ' => 'e',
            'ί' => 'i',
            'ό' => 'o',
            'ύ' => 'y',
            'ή' => 'h',
            'ώ' => 'w',
            'ς' => 's',
            'ϊ' => 'i',
            'ΰ' => 'y',
            'ϋ' => 'y',
            'ΐ' => 'i',
            'Α' => 'A',
            'Β' => 'B',
            'Γ' => 'G',
            'Δ' => 'D',
            'Ε' => 'E',
            'Ζ' => 'Z',
            'Η' => 'H',
            'Θ' => '8',
            'Ι' => 'I',
            'Κ' => 'K',
            'Λ' => 'L',
            'Μ' => 'M',
            'Ν' => 'N',
            'Ξ' => '3',
            'Ο' => 'O',
            'Π' => 'P',
            'Ρ' => 'R',
            'Σ' => 'S',
            'Τ' => 'T',
            'Υ' => 'Y',
            'Φ' => 'F',
            'Χ' => 'X',
            'Ψ' => 'PS',
            'Ω' => 'W',
            'Ά' => 'A',
            'Έ' => 'E',
            'Ί' => 'I',
            'Ό' => 'O',
            'Ύ' => 'Y',
            'Ή' => 'H',
            'Ώ' => 'W',
            'Ϊ' => 'I',
            'Ϋ' => 'Y'
        ),
        'turkish_map' => array(
            'ş' => 's',
            'Ş' => 'S',
            'ı' => 'i',
            'İ' => 'I',
            'ç' => 'c',
            'Ç' => 'C',
            'ü' => 'u',
            'Ü' => 'U',
            'ö' => 'o',
            'Ö' => 'O',
            'ğ' => 'g',
            'Ğ' => 'G'
        ),
        'russian_map' => array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'yo',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sh',
            'ъ' => '',
            'ы' => 'y',
            'ь' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'Yo',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'J',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sh',
            'Ъ' => '',
            'Ы' => 'Y',
            'Ь' => '',
            'Э' => 'E',
            'Ю' => 'Yu',
            'Я' => 'Ya'
        ),
        'ukrainian_map' => array(
            'Є' => 'Ye',
            'І' => 'I',
            'Ї' => 'Yi',
            'Ґ' => 'G',
            'є' => 'ye',
            'і' => 'i',
            'ї' => 'yi',
            'ґ' => 'g'
        ),
        'czech_map' => array(
            'č' => 'c',
            'ď' => 'd',
            'ě' => 'e',
            'ň' => 'n',
            'ř' => 'r',
            'š' => 's',
            'ť' => 't',
            'ů' => 'u',
            'ž' => 'z',
            'Č' => 'C',
            'Ď' => 'D',
            'Ě' => 'E',
            'Ň' => 'N',
            'Ř' => 'R',
            'Š' => 'S',
            'Ť' => 'T',
            'Ů' => 'U',
            'Ž' => 'Z'
        ),
        'polish_map' => array(
            'ą' => 'a',
            'ć' => 'c',
            'ę' => 'e',
            'ł' => 'l',
            'ń' => 'n',
            'ó' => 'o',
            'ś' => 's',
            'ź' => 'z',
            'ż' => 'z',
            'Ą' => 'A',
            'Ć' => 'C',
            'Ę' => 'e',
            'Ł' => 'L',
            'Ń' => 'N',
            'Ó' => 'O',
            'Ś' => 'S',
            'Ź' => 'Z',
            'Ż' => 'Z'
        ),
        'latvian_map' => array(
            'ā' => 'a',
            'č' => 'c',
            'ē' => 'e',
            'ģ' => 'g',
            'ī' => 'i',
            'ķ' => 'k',
            'ļ' => 'l',
            'ņ' => 'n',
            'š' => 's',
            'ū' => 'u',
            'ž' => 'z',
            'Ā' => 'A',
            'Č' => 'C',
            'Ē' => 'E',
            'Ģ' => 'G',
            'Ī' => 'i',
            'Ķ' => 'k',
            'Ļ' => 'L',
            'Ņ' => 'N',
            'Š' => 'S',
            'Ū' => 'u',
            'Ž' => 'Z'
        )
    );

    /**
     * List of words to remove from URLs.
     */
    public static $remove_list = array(
    );

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
    private static function init() {
        if (count(self::$map) > 0) {
            return;
        }

        foreach (self::$maps as $map) {
            foreach ($map as $orig => $conv) {
                self::$map [$orig] = $conv;
                self::$chars .= $orig;
            }
        }

        self::$regex = '/[' . self::$chars . ']/u';
    }

    /**
     * Add new characters to the list.
     * `$map` should be a hash.
     */
    public static function add_chars($map) {
        if (!is_array($map)) {
            throw new LogicException('$map must be an associative array.');
        }
        self::$maps [] = $map;
        self::$map = array();
        self::$chars = '';
    }

    /**
     * Append words to the remove list.
     * Accepts either single words
     * or an array of words.
     */
    public static function remove_words($words) {
        $words = is_array($words) ? $words : array(
            $words
                );
        self::$remove_list = array_merge(self::$remove_list, $words);
    }

    /**
     * Transliterates characters to their ASCII equivalents.
     */
    public static function downcode($text) {
        self::init();

        if (preg_match_all(self::$regex, $text, $matches)) {
            for ($i = 0; $i < count($matches [0]); $i++) {
                $char = $matches [0] [$i];
                if (isset(self::$map [$char])) {
                    $text = str_replace($char, self::$map [$char], $text);
                }
            }
        }
        return $text;
    }

    /**
     * Filters a string, e.g., "Petty theft" to "petty-theft"
     */
    public static function filter($text, $length = 60) {
        $text = self::downcode($text);

        // remove all these words from the string before urlifying
        $text = preg_replace('/\b(' . join('|', self::$remove_list) . ')\b/i', '', $text);

        // if downcode doesn't hit, the char will be stripped here
        $text = preg_replace('/[^-\w\s]/', '', $text); // remove unneeded chars
        $text = preg_replace('/^\s+|\s+$/', '', $text); // trim
        // leading/trailing
        // spaces
        $text = preg_replace('/[-\s]+/', '-', $text); // convert spaces to
        // hyphens
        $text = strtolower($text); // convert to lowercase
        return trim(substr($text, 0, $length), '-'); // trim to first
        // $length
        // chars
    }

    /**
     * Alias of `URLify::downcode()`.
     */
    public static function transliterate($text) {
        return self::downcode($text);
    }

}
