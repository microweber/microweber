<?php


$language_content_saved = false;
$mw_new_language_entires = array();
;

/**
 * Saves the language file after page load
 * @package Language
 * @internal
 */
function __store_lang_file()
{

    global $language_content_saved;


    if ($language_content_saved == true) {
        return;
    }
    if (is_admin() == false) {
        return false;
    }
    global $mw_new_language_entires;
    $language_content = get_language_file_content();

    $lang = current_lang();

    $lang_file = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.php';
    $language_content2 = array();
    if (is_array($language_content) and is_array($mw_new_language_entires) and !empty($mw_new_language_entires)) {

            $language_content2 = $mw_new_language_entires;

            if (!empty($language_content2)) {
                foreach ($language_content2 as $key => $value) {

                    if (!isset($language_content[$key])) {

                        $language_content[$key] = $value;
                    }
                }


        }


        $language_content = array_unique($language_content);


        $lang_file_str = '<?php ' . "\n";
        $lang_file_str .= ' $language=array();' . "\n";
        foreach ($language_content as $key => $value) {

            $value = addslashes($value);
            $lang_file_str .= '$language["' . $key . '"]' . "= '{$value}' ; \n";

        }
        $language_content_saved = 1;
        if (is_admin() == true) {
            $c1 = count($language_content);
            $c2 = count($language_content2);


            if ($c1 > $c2) {
				if(isset($lang_file) and $lang_file != false and isset($lang_file_str) and $lang_file_str != false){
					 file_put_contents($lang_file, $lang_file_str);
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


    if (!defined('MW_LANG') and isset($lang)) {
        define('MW_LANG', $lang);
    }

    if (defined('MW_LANG') and MW_LANG != false) {
        return MW_LANG;
    }


    $lang = false;


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

    if (!defined('MW_LANG') and isset($lang)) {
        define('MW_LANG', $lang);
    }


    return $lang;

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

    $k1 = mw('url')->slug($k);

    $lang = current_lang();

    $language_content = get_language_file_content();

    if (isset($language_content[$k1]) == false) {
        if (is_admin() == true) {
            $k2 = ($k);
            $mw_new_language_entires[$k1] = $k2;
            $language_content[$k1] = $k2;
            $scheduler = new \Microweber\Utils\Events();
            // schedule a global scope function:
            $scheduler->registerShutdownEvent("__store_lang_file");

        }
        if ($to_return == true) {
            return $k;
        }
        print $k;
    } else {
        if ($to_return == true) {
            return $language_content[$k1];
        }
        print $language_content[$k1];
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

        $cust_dir = $lang_file = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;
        if (!is_dir($cust_dir)) {
            mkdir_recursive($cust_dir);
        }

        $language_content = $data;

        $lang_file = $cust_dir . $lang . '.php';

        if (is_array($language_content)) {
            $language_content = array_unique($language_content);

            $lang_file_str = '<?php ' . "\n";
            $lang_file_str .= ' $language=array();' . "\n";
            foreach ($language_content as $key => $value) {

                $value = addslashes($value);
                $lang_file_str .= '$language["' . $key . '"]' . "= '{$value}' ; \n";

            }
            $language_content_saved = 1;
            if (is_admin() == true) {
                file_put_contents($lang_file, $lang_file_str);
            }
        }
        return array('success' => 'Language file [' . $lang . '] is updated');


    }


}

$language_content = array();
/**
 * Gets all the language file contents
 * @internal its used via ajax in the admin panel under Settings->Language
 * @package Language
 */
function get_language_file_content()
{
    global $language_content;

    if (!empty($language_content)) {
        return $language_content;
    }


    $lang = current_lang();

    $lang_file = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file = normalize_path($lang_file, false);

    $lang_file2 = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file3 = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en.php';


    if (is_file($lang_file2)) {
        include ($lang_file2);

        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($language_content[$k]) == false) {
                    $language_content[$k] = $v;
                }
            }
        }
    }


    if (is_file($lang_file)) {
        include ($lang_file);

        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($language_content[$k]) == false) {

                    $language_content[$k] = $v;
                }
            }
        }
    }
    if (is_file($lang_file3)) {
        include ($lang_file3);

        if (isset($language) and is_array($language)) {
            foreach ($language as $k => $v) {
                if (isset($language_content[$k]) == false) {

                    $language_content[$k] = $v;
                }
            }
        }
    }

    return $language_content;


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

    $lang_dir = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR;


    $files = array();
    $directory = opendir($lang_dir);
    while ($item = readdir($directory)) {


        if (($item != ".") && ($item != "..") && ($item != ".svn")) {
            $item = no_ext($item);
            if (trim($item != "")) {
                $mw_all_langs[] = $item;
            }
        }
    }

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


    $lang_file = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'help' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file_en = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'help' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file = normalize_path($lang_file, false);

    if (is_file($lang_file)) {
        include($lang_file);
    } else if (is_file($lang_file_en)) {
        return $lang_file_en;
    }

}