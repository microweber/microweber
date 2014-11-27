<?php




$mw_language_content_saved = false;
$mw_new_language_entires = array();
$mw_new_language_entires_ns = array();
// from here http://stackoverflow.com/a/7709863/731166
function ewchar_to_utf8($matches)
{
    $ewchar = $matches[1];
    $binwchar = hexdec($ewchar);
    $wchar = chr(($binwchar >> 8) & 0xFF) . chr(($binwchar) & 0xFF);
    return iconv("unicodebig", "utf-8", $wchar);
}

function special_unicode_to_utf8($str)
{
    return preg_replace_callback("/\\\u([[:xdigit:]]{4})/i", "ewchar_to_utf8", $str);
}

function __store_lang_file_ns()
{
    global $mw_new_language_entires_ns;
    global $mw_new_language_entires_ns;

    if (!empty($mw_new_language_entires_ns)) {
        foreach ($mw_new_language_entires_ns as $k => $v) {
            $namespace = $k;
            $lang = current_lang();

            $lang_file = userfiles_path() . $namespace . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';
            $lang_file = normalize_path($lang_file, false);

            $lang_file2 = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $lang . '.json';
            $lang_file2 = normalize_path($lang_file2, false);

            $lang_file3 = userfiles_path() . $namespace . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en.json';
            $lang_file3 = normalize_path($lang_file3, false);

            $lang_file_save = false;
            $existing = $mw_new_language_entires_ns[$k];
            if (is_array($v)) {
                $existing = array_merge($existing, $v);
            }

            if (is_file($lang_file)) {
                $lang_file_content = file_get_contents($lang_file);
                $lang_file_content = @json_decode($lang_file_content, true);
                if (is_array($lang_file_content)) {
                    $existing = array_merge($existing, $lang_file_content);
                }
            }
            if (is_file($lang_file2)) {
                $lang_file_content = file_get_contents($lang_file2);
                $lang_file_content = @json_decode($lang_file_content, true);
                if (is_array($lang_file_content)) {
                    $existing = array_merge($existing, $lang_file_content);
                }
            }
            if (is_file($lang_file3)) {
                $lang_file_content = file_get_contents($lang_file3);
                $lang_file_content = @json_decode($lang_file_content, true);
                if (is_array($lang_file_content)) {
                    $existing = array_merge($existing, $lang_file_content);
                }
            }


            if (is_array($existing) and !empty($existing)) {


                $dn = dirname($lang_file);
                if (!is_dir($dn)) {
                    @mkdir($dn);
                }
                @touch($lang_file);
                @touch($lang_file2);
                @touch($lang_file3);
                if (!is_dir($dn)) {
                    $dn = dirname($lang_file2);
                    @mkdir_recursive($dn);
                }
                if (!is_dir($dn)) {
                    $dn = dirname($lang_file3);
                    @mkdir_recursive($dn);
                }

                $store_file = false;
                if (is_writable($lang_file)) {
                    $store_file = $lang_file;
                } elseif (is_writable($lang_file2)) {
                    $store_file = $lang_file2;

                } elseif (is_writable($lang_file3)) {
                    $store_file = $lang_file3;

                }

                if ($store_file != false) {
                    $lang_file_str = json_encode($existing);
                    $lang_file = $store_file;
                }


                if (isset($lang_file_str) and $lang_file_str != false) {

                    if (function_exists('iconv')) {
                        $lang_file_str = special_unicode_to_utf8($lang_file_str);
                    }


                    $lang_file_str = str_replace('","', '",' . "\n" . '"', $lang_file_str);
                    if (is_writable($lang_file) and is_string($lang_file_str) and $lang_file_str != '') {
                        @file_put_contents($lang_file, $lang_file_str);
                    }
                }


            }

        }
    }
    return;

}

/**
 * Saves the language file after page load
 * @package Language
 * @internal
 */
function __store_lang_file()
{

    global $mw_language_content_saved;


    if ($mw_language_content_saved == true) {
        return;
    }
    if (is_admin() == false) {
        return false;
    }
    global $mw_new_language_entires;
    $mw_language_content = get_language_file_content();

    $lang = current_lang();


    $lang_file = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';
    if (!is_array($mw_language_content) or empty($mw_language_content)) {
        $lang_file_str = json_encode($mw_new_language_entires);
        // if(is_writable($lang_file)){
        //d($lang_file);
        @file_put_contents($lang_file, $lang_file_str);
        // }
    }
    $mw_language_content2 = array();
    if (is_array($mw_language_content) and is_array($mw_new_language_entires) and !empty($mw_new_language_entires)) {

        $mw_language_content2 = $mw_new_language_entires;

        if (!empty($mw_language_content2)) {
            foreach ($mw_language_content2 as $key => $value) {

                if (!isset($mw_language_content[$key])) {

                    $mw_language_content[$key] = $value;
                }
            }


        }


        $mw_language_content = array_unique($mw_language_content);


        $lang_file_str = json_encode($mw_language_content);

        $mw_language_content_saved = 1;
        if (is_admin() == true) {
            $c1 = count($mw_language_content);
            $c2 = count($mw_language_content2);


            if ($c1 > $c2) {
                if (isset($lang_file) and $lang_file != false and isset($lang_file_str) and $lang_file_str != false) {
                    $dn = dirname($lang_file);
                    if (!is_dir($dn)) {
                        @mkdir($dn);
                    }
                    if (isset($lang_file_str) and $lang_file_str != false) {
                        if (!is_file($lang_file)) {
                            touch($lang_file);
                        }

                        if (function_exists('iconv')) {
                            $lang_file_str = special_unicode_to_utf8($lang_file_str);
                        }


                        $lang_file_str = str_replace('","', '",' . "\n" . '"', $lang_file_str);
                        if (is_writable($lang_file) and is_string($lang_file_str) and $lang_file_str != '') {
                            @file_put_contents($lang_file, $lang_file_str);
                        }
                    }

                }

            }
        }
    }

}

/**
 * Get the current language of the site
 *
 * @example
 * <code>
 *  $current_lang = current_lang();
 *  print $current_lang;
 * </code>
 *
 * @package Language
 * @constant  MW_LANG defines the MW_LANG constant
 */
function current_lang()
{

   static $installed = null;

    if($installed === null){
        $installed = Config::get('microweber.is_installed');
    }
    if($installed == false){
       return 'en';
    }



    $lang = false;
    if (defined('MW_LANG') and MW_LANG != false) {
        $lang = MW_LANG;
        return MW_LANG;
    }


    if (!isset($lang) or $lang == false) {
        if (isset($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
        }
    }
    if (!isset($lang) or $lang == false) {
        $def_language = get_option('language', 'website');
        if ($def_language != false) {
            $lang = $def_language;
        }
    }
    if (!isset($lang) or $lang == false) {
        $lang = 'en';
    }
    //$lang = str_replace('..', '', $lang);
    $lang = str_replace('.', '', $lang);
    $lang = str_replace(DIRECTORY_SEPARATOR, '', $lang);

    if (!defined('MW_LANG') and isset($lang)) {
        define('MW_LANG', $lang);
    }


    return $lang;

}

function _lang($title, $namespace = false)
{

    print lang($title, $namespace);

}

function lang($title, $namespace = false)
{

    static $lang_file;
    global $mw_language_content;
    global $mw_new_language_entires_ns;
    $k = $title;
    $k1 = mw()->url->slug($k);

    $lang = current_lang();

    $mw_language_content_file = get_language_file_content($namespace);

    if (isset($mw_language_content_file[$k1]) == false) {
        if (isset($mw_language_content[$k1]) != false) {
            return $mw_language_content[$k1];
        }
        if (is_admin() == true) {
            $namespace = trim($namespace);
            $namespace = str_replace(' ', '', $namespace);
            $namespace = str_replace('..', '', $namespace);
            $namespace = str_replace('\\', '/', $namespace);
            if (!isset($mw_new_language_entires_ns[$namespace])) {
                $mw_new_language_entires_ns[$namespace] = array();
            }
            $k2 = ($k);
            $mw_new_language_entires_ns[$namespace][$k1] = $k2;

            //  $mw_language_content[$k1] = $k2;
            if (!defined('MW_LANG_STORE_ON_EXIT_EVENT_BINDED_NS')) {
                define('MW_LANG_STORE_ON_EXIT_EVENT_BINDED_NS', 1);
                // __store_lang_file_ns();
                //$scheduler = new \Microweber\Utils\Events();
                // schedule a global scope function:
               // $scheduler->registerShutdownEvent("__store_lang_file_ns");
            }


        }
        return $k;
    } else {
        return $mw_language_content_file[$k1];

    }


}

/**
 * Prints a string in the current language
 *
 * @example
 * <code>
 *   //print something in the user language
 *  _e('Pages');
 * </code>
 * @example
 * <code>
 *   //get a string in the user language
 *  $pages_string = _e('Pages',1);
 * print $pages_string;
 * </code>
 *
 * @package Language
 * @use current_lang()
 */
function _e($k, $to_return = false)
{

    static $lang_file;
    global $mw_new_language_entires;

    $k1 = mw()->url->slug($k);

    $lang = current_lang();

    $mw_language_content = get_language_file_content();

    if (isset($mw_language_content[$k1]) == false) {
        if (is_admin() == true) {
            $k2 = ($k);
            $mw_new_language_entires[$k1] = $k2;
            $mw_language_content[$k1] = $k2;
            if (!defined('MW_LANG_STORE_ON_EXIT_EVENT_BINDED')) {
                define('MW_LANG_STORE_ON_EXIT_EVENT_BINDED', 1);
               // $scheduler = new \Microweber\Utils\Events();
                // schedule a global scope function:
              //  $scheduler->registerShutdownEvent("__store_lang_file");
            }


        }
        if ($to_return == true) {
            return $k;
        }
        print $k;
    } else {
        if ($to_return == true) {
            return $mw_language_content[$k1];
        }
        print $mw_language_content[$k1];
    }


}


api_expose('send_lang_form_to_microweber');
/**
 * Send your language translation to Microweber
 * @internal its used via ajax in the admin panel under Settings->Language
 * @package Language
 */
function send_lang_form_to_microweber($data)
{
    if (is_admin() == true) {
        $lang = current_lang();

        $send = array();
        $send['function_name'] = __FUNCTION__;
        $send['language'] = $lang;
        $send['data'] = $data;
        return mw_send_anonymous_server_data($send);


    }

}

api_expose('save_language_file_content');
/**
 * Saves your custom language translation
 * @internal its used via ajax in the admin panel under Settings->Language
 * @package Language
 */
function save_language_file_content($data)
{

    if (isset($_POST) and !empty($_POST)) {
        $data = $_POST;
    }
    if (is_admin() == true) {
        if (isset($data['unicode_temp_remove'])) {
            unset($data['unicode_temp_remove']);
        }


        $lang = current_lang();

        // $cust_dir = $lang_file = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;

        $cust_dir = userfiles_path() . 'language' . DIRECTORY_SEPARATOR;


        if (!is_dir($cust_dir)) {
            mkdir_recursive($cust_dir);
        }

        $mw_language_content = $data;

        $lang_file = $cust_dir . $lang . '.json';

        if (is_array($mw_language_content)) {
            $mw_language_content = array_unique($mw_language_content);
            $lang_file_str = json_encode($mw_language_content);

//            $lang_file_str = '<?php ' . "\n";
//            $lang_file_str .= ' $language=array();' . "\n";
//            foreach ($mw_language_content as $key => $value) {
//
//                $value = addslashes($value);
//                $lang_file_str .= '$language["' . $key . '"]' . "= '{$value}' ; \n";
//
//            }
            $mw_language_content_saved = 1;
            if (is_admin() == true) {
                file_put_contents($lang_file, $lang_file_str);
            }
        }
        return array('success' => 'Language file [' . $lang . '] is updated');


    }


}

$mw_language_content = array();
$mw_language_content_namespace = array();
/**
 * Gets all the language file contents
 * @internal its used via ajax in the admin panel under Settings->Language
 * @package Language
 */
function get_language_file_content($namespace = false)
{
    if ($namespace == false) {
        return _mw_get_language_file_content_core();
    } elseif ($namespace != false) {
        return _mw_get_language_file_content_namespaced($namespace);
    }


}

function _mw_get_language_file_content_core()
{
    global $mw_language_content;

    if (!empty($mw_language_content)) {
        return $mw_language_content;
    }


    $lang = current_lang();

    $lang_file = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';
    $lang_file = normalize_path($lang_file, false);

    $lang_file2 = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';

    // . 'storage' . DS

    $lang_file3 = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . 'en.json';


    if (is_file($lang_file2)) {
        $language_str = file_get_contents($lang_file2);
        $language = json_decode($language_str, true);
        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($mw_language_content[$k]) == false) {
                    $mw_language_content[$k] = $v;
                }
            }
        }
    }


    if (is_file($lang_file)) {
        $language_str = file_get_contents($lang_file);
        $language = json_decode($language_str, true);

        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($mw_language_content[$k]) == false) {

                    $mw_language_content[$k] = $v;
                }
            }
        }
    }
    if (is_file($lang_file3)) {
        $language_str = file_get_contents($lang_file3);
        $language = json_decode($language_str, true);
        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($mw_language_content[$k]) == false) {


                    $mw_language_content[$k] = $v;
                }
            }
        }
    }

    return $mw_language_content;


}

function _mw_get_language_file_content_namespaced($namespace)
{
    if ($namespace == false) {
        return false;
    }

    global $mw_language_content_namespace;
    $namespace = trim($namespace);
    $namespace = str_replace(' ', '', $namespace);
    $namespace = str_replace('..', '', $namespace);
    $namespace = str_replace('\\', '/', $namespace);
    if (isset($mw_language_content_namespace[$namespace]) and !empty($mw_language_content_namespace[$namespace])) {
        return $mw_language_content_namespace[$namespace];
    }


    $lang = current_lang();

    $lang_file = userfiles_path() . $namespace . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';
    $lang_file = normalize_path($lang_file, false);

    $lang_file2 = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $lang . '.json';
    $lang_file2 = normalize_path($lang_file2, false);

    $lang_file3 = userfiles_path() . $namespace . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en.json';
    $lang_file3 = normalize_path($lang_file3, false);


    if (!isset($mw_language_content_namespace[$namespace])) {
        $mw_language_content_namespace[$namespace] = array();
    }
    if (is_file($lang_file2)) {
        $language_str = file_get_contents($lang_file2);
        $language = json_decode($language_str, true);
        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($mw_language_content[$namespace][$k]) == false) {
                    $mw_language_content_namespace[$namespace][$k] = $v;
                }
            }
        }
    }


    if (is_file($lang_file)) {
        $language_str = file_get_contents($lang_file);
        $language = json_decode($language_str, true);

        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($mw_language_content_namespace[$namespace][$k]) == false) {

                    $mw_language_content_namespace[$namespace][$k] = $v;
                }
            }
        }
    }
    if (is_file($lang_file3)) {
        $language_str = file_get_contents($lang_file3);
        $language = json_decode($language_str, true);
        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($mw_language_content_namespace[$namespace][$k]) == false) {


                    $mw_language_content_namespace[$namespace][$k] = $v;
                }
            }
        }
    }

    return $mw_language_content_namespace[$namespace];


}

$mw_all_langs = array();
/**
 * Get all available languages as array
 *
 * To set user language you must create cookie named "lang"
 *
 * @return array The languages array
 * @package Language
 * @example
 * <code>
 * //get all languages
 * $langs = get_available_languages();
 * var_dump($langs);
 * </code>
 *
 * <code>
 * //set language for the user
 *  setcookie("lang", 'en'); //sets english language
 * </code>
 */
function get_available_languages()
{
    global $mw_all_langs;

    if (!empty($mw_all_langs)) {
        return $mw_all_langs;
    }

    $lang_dir = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR;


    $files = array();
    foreach (glob($lang_dir . "*.json") as $filename) {
        $item = basename($filename);
        $item = no_ext($item);
        $mw_all_langs[] = $item;
    }
//    $directory = opendir($lang_dir);
//    while ($item = readdir($directory)) {
//
//
//        if (($item != ".") && ($item != "..") && ($item != ".svn")) {
//            $item = no_ext($item);
//            if (trim($item != "")) {
//                $mw_all_langs[] = $item;
//            }
//        }
//    }

    return $mw_all_langs;

}

/**
 * Shows a section of the help file
 * @internal its used on the help in the admin
 * @package Language
 *
 */
function show_help($section = 'main')
{
    $lang = current_lang();

    $lang = str_replace('..', '', $lang);
    if (trim($lang) == '') {
        $lang = 'en';
    }


    $lang_file =mw_includes_path(). 'help' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file_en = mw_includes_path() .  'help' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file = normalize_path($lang_file, false);

    if (is_file($lang_file)) {
        include($lang_file);
    } else if (is_file($lang_file_en)) {
        return $lang_file_en;
    }

}



