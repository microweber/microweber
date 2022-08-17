<?php

namespace MicroweberPackages\App\Managers\Helpers;

use MicroweberPackages\Event\Event;
use MicroweberPackages\Translation\LanguageHelper;


$mw_language_content = array();
$mw_language_content_namespace = array();


$mw_language_content_saved = false;
$mw_new_language_entries = array();
$mw_new_language_entries_ns = array();
$mw_all_langs = array();


class Lang
{
    public $is_enabled = null;
    public $lang = null;
    private $__default_lang_option = false;


    public function __construct()
    {
        if (mw_is_installed()) {
            $this->is_enabled = true;
        }

        if ($this->is_enabled) {
            $this->__default_lang_option = get_option('language', 'website');
        }
    }


    function set_current_lang($lang = 'en')
    {
        $lang = str_replace('.', '', $lang);
        $lang = str_replace(DIRECTORY_SEPARATOR, '', $lang);
        $lang = filter_var($lang, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        $this->clearCache();

        // must not clear options cache here
        //  mw()->option_manager->clear_memory();

        $this->lang = $lang;
        return app()->setLocale($lang);
    }

    /**
     * Get the current language of the site.
     *
     * @example
     *
     * <code>
     *  $current_lang = current_lang();
     *  print $current_lang;
     * </code>
     */

    function current_lang()
    {
        $locale = app()->getLocale();
        if($locale ==='en'){
            $locale = 'en_US'; // for the multi language
        }

        return $locale;
    }

    function current_lang_display()
    {
        if (isset($_COOKIE['lang_display'])) {
            return $_COOKIE['lang_display'];
        }

        return $this->current_lang();
    }

    public static $_defaultLang = false;

    public function clearCache(){
        self::$_defaultLang = null;
    }

    function default_lang()
    {
        if ($this->is_enabled && self::$_defaultLang) {
            return self::$_defaultLang;
        }

        $lang = app()->getLocale();

        if ($this->is_enabled) {
            $lang_opt = get_option('language', 'website');
            if ($lang_opt) {
                $lang = $lang_opt;
                self::$_defaultLang = $lang;
            }
        }

        return $lang;
    }

    function __store_lang_file_ns($lang = false)
    {
        if (!is_admin()) {
            return;
        }

        global $mw_new_language_entries_ns;

        if (!$lang) {
            $lang = current_lang();
        }
        if (!empty($mw_new_language_entries_ns)) {
            foreach ($mw_new_language_entries_ns as $k => $v) {
                $namespace = $k;
                $namespace = str_replace('\\', '/', $namespace);
                //$lang_file = userfiles_path() . $namespace . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';
                $lang_file = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $lang . '.json';
                $lang_file = normalize_path($lang_file, false);

//                $lang_file2 = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $lang . '.json';
//                $lang_file2 = normalize_path($lang_file2, false);
//
//                $lang_file3 = userfiles_path() . $namespace . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en.json';
//                $lang_file3 = normalize_path($lang_file3, false);

                $lang_file_save = false;
                $existing = $mw_new_language_entries_ns[$k];
                if (is_array($v)) {
                    $existing = array_merge($existing, $v);
                }

                if (!is_dir(dirname($lang_file))) {
                    @mkdir_recursive(dirname($lang_file));
                }

                if (!is_file($lang_file)) {
                    @touch($lang_file);
                }

                if (is_file($lang_file)) {
                    $lang_file_content = file_get_contents($lang_file);
                    $lang_file_content = @json_decode($lang_file_content, true);
                    if (is_array($lang_file_content)) {
                        $existing = array_merge($existing, $lang_file_content);
                    }
                }
//                if (is_file($lang_file2)) {
//                    $lang_file_content = file_get_contents($lang_file2);
//                    $lang_file_content = @json_decode($lang_file_content, true);
//                    if (is_array($lang_file_content)) {
//                        $existing = array_merge($existing, $lang_file_content);
//                    }
//                }
//                if (is_file($lang_file3)) {
//                    $lang_file_content = file_get_contents($lang_file3);
//                    $lang_file_content = @json_decode($lang_file_content, true);
//                    if (is_array($lang_file_content)) {
//                        $existing = array_merge($existing, $lang_file_content);
//                    }
//                }

                if (is_array($existing) and !empty($existing)) {
                    $dn = dirname($lang_file);
                    if (!is_dir($dn)) {
                        @mkdir($dn);
                    }
                    if (!is_file($lang_file)) {
                        @touch($lang_file);
                    }
//                    if (!is_file($lang_file2)) {
//                        @touch($lang_file2);
//                    }
//                    if (!is_file($lang_file3)) {
//                        @touch($lang_file3);
//                    }
//
//                    if (!is_dir($dn)) {
//                        $dn = dirname($lang_file2);
//                        @mkdir_recursive($dn);
//                    }
//                    if (!is_dir($dn)) {
//                        $dn = dirname($lang_file3);
//                        @mkdir_recursive($dn);
//                    }

                    $store_file = false;
                    if (is_writable($lang_file)) {
                        $store_file = $lang_file;
                    }
//                    elseif (is_writable($lang_file2)) {
//                        $store_file = $lang_file2;
//                    } elseif (is_writable($lang_file3)) {
//                        $store_file = $lang_file3;
//                    }

                    if ($store_file != false) {
                        $lang_file_str = json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                        $lang_file = $store_file;
                    }

                    if (isset($lang_file_str) and $lang_file_str != false) {
                        if (function_exists('iconv')) {
                            $lang_file_str = special_unicode_to_utf8($lang_file_str);
                        }
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
     * Saves the language file after page load.
     *
     * @internal
     */
    function __store_lang_file($lang = false)
    {
        if (!is_admin()) {
            return;
        }
        $lang_files_dir = userfiles_path() . 'language' . DIRECTORY_SEPARATOR;
        if (!is_dir($lang_files_dir)) {
            @mkdir_recursive($lang_files_dir);
        }

        global $mw_language_content_saved;

//        if ($mw_language_content_saved == true) {
//            return;
//        }

        global $mw_new_language_entries;
        $mw_language_content = $this->_mw_get_language_file_content_core();

        if (!$lang) {
            $lang = current_lang();
        }
        $lang_file = $lang_files_dir . $lang . '.json';

        if (is_array($mw_new_language_entries) and !empty($mw_new_language_entries)) {

            $mw_new_language_entries = array_merge($mw_new_language_entries, $mw_language_content);

            //$mw_new_language_entries = array_unique($mw_new_language_entries);
            $lang_file_str = json_encode($mw_new_language_entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            if (function_exists('iconv')) {
                $lang_file_str = special_unicode_to_utf8($lang_file_str);
            }
            @file_put_contents($lang_file, $lang_file_str);

        }
        return;

    }


    function lang_attributes()
    {
        $lang = $lang_curr = current_lang();

        /*  if (mb_strlen($lang) > 2) {
              $lang = mb_substr($lang, 0, 2);
          }*/

        $lang = str_replace('_', '-', $lang);

        $attr = array(
            'lang="' . $lang . '"'
        );
        $dir = 'ltr';
        $is_rtl = $this->lang_is_rtl($lang_curr);
        if ($is_rtl) {
            $dir = 'rtl';

        }
        array_push($attr, 'dir="' . $dir . '"');
        return implode(' ', $attr);

    }

    /**
     * Prints a string in the current language.
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
     * @use current_lang()
     */
    function e($k, $to_return = false)
    {
        $string = $this->lang($k);

        if ($to_return == true) {
            return $string;
        }
        echo $string;

        return;
    }

    function ejs($k, $to_return = false)
    {
        $string = $this->lang($k);
        $string = htmlspecialchars($string, ENT_QUOTES);

        if ($to_return == true) {
            return $string;
        }
        echo $string;

        return;
    }

    private function __make_lang_key_suffix($str)
    {
        if (function_exists('iconv')) {
            $str = $this->__convert_to_utf($str);
        }

        $hash = array();
        $all_words = explode(' ', $str);
        foreach ($all_words as $word) {
            $first = mb_substr($word, 0, 1);
            $hash[] = $first;
        }

        $count_of_space = mb_substr_count($str, ' ');
        $count_chars = mb_strlen($str);
        $hash[] = $count_chars;
        return implode('', $hash);

    }

    private function __convert_to_utf($text)
    {

        $encoding = mb_detect_encoding($text, mb_detect_order(), false);

        if ($encoding == "UTF-8") {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }


        $out = iconv(mb_detect_encoding($text, mb_detect_order(), false), "UTF-8//IGNORE", $text);


        return $out;
    }


    function lang($title, $namespace = false)
    {
        global $mw_language_content;
        global $mw_new_language_entries_ns;
        global $mw_new_language_entries;
        $title_value = $title;
        //$k1 = strip_tags($k);
        $k1 = url_title($title_value);
        $environment = \App::environment();


        $lang_key = preg_replace("/[^[:alnum:][:space:]]/u", '', $title_value);
        $lang_key = preg_replace('/(\v|\s)+/', ' ', $lang_key);

        $sufix = $this->__make_lang_key_suffix($lang_key);

        $translation_key = $k1 . '-key-' . $sufix;


        $lang = current_lang();

        $mw_language_content_file = $this->get_language_file_content($namespace);

        if (isset($mw_language_content_file[$translation_key]) != false) {
            $title_value = $mw_language_content_file[$translation_key];
            $k1 = $translation_key;

        } else if (isset($mw_language_content_file[$k1]) != false) {
            $title_value = $mw_language_content_file[$k1];
            $k1 = $translation_key;
            $mw_new_language_entries[$k1] = $title_value;
        }


        if (isset($mw_language_content_file[$k1]) == false) {

            if (!$namespace) {
                $k2 = ($title_value);
                $mw_new_language_entries[$k1] = $k2;
                $mw_language_content[$k1] = $k2;

                if (!defined('MW_LANG_STORE_ON_EXIT_EVENT_BINDED')) {
                    define('MW_LANG_STORE_ON_EXIT_EVENT_BINDED', 1);
                    $scheduler = new Event();
                    // schedule a global scope function:
                    if ($environment != 'testing') {
                        $scheduler->registerShutdownEvent('__store_lang_file', $lang);
                    }
                    // $scheduler->registerShutdownEvent('__store_lang_file');
                }
            } else {
                $namespace = trim($namespace);
                $namespace = str_replace(' ', '', $namespace);
                $namespace = str_replace('..', '', $namespace);
                $namespace = str_replace('\\', '/', $namespace);
                if (!isset($mw_new_language_entries_ns[$namespace])) {
                    $mw_new_language_entries_ns[$namespace] = array();
                }

                if (!isset($mw_new_language_entries_ns[$namespace][$k1])) {
                    $k2 = ($title_value);
                    $mw_new_language_entries_ns[$namespace][$k1] = $k2;
                    // $mw_language_content_file[$k1] = $k2;

                    if (!defined('MW_LANG_STORE_ON_EXIT_EVENT_BINDED_NS')) {
                        define('MW_LANG_STORE_ON_EXIT_EVENT_BINDED_NS', 1);
                        $scheduler = new Event();
                        if ($environment != 'testing') {
                            $scheduler->registerShutdownEvent('__store_lang_file_ns', $lang);
                        }
                    }
                }


            }


            return $title_value;
        } else {
            return $mw_language_content_file[$k1];
        }
    }

    /**
     * Gets all the language file contents.
     *
     * @internal its used via ajax in the admin panel under Settings->Language
     */
    function get_language_file_content($namespace = false)
    {

        if ($namespace == false) {
            return $this->_mw_get_language_file_content_core();
        } elseif ($namespace != false) {
            return $this->_mw_get_language_file_content_namespaced($namespace);
        }
    }


    function get_all_language_file_namespaces()
    {


        $lang_files_dir = userfiles_path() . 'language' . DIRECTORY_SEPARATOR;

        if (!is_dir($lang_files_dir)) {
            @mkdir_recursive($lang_files_dir);
        }

        $list = $this->_rsearch($lang_files_dir, '.json');
        $ns = array();
        if ($list) {

            foreach ($list as $l) {
                $dir = dirname($l);
                if ($dir and stristr($dir, $lang_files_dir) and is_dir($dir)) {
                    $dir = str_replace($lang_files_dir, '', $dir);
                    $namespace = str_replace(' ', '', $dir);
                    $namespace = str_replace('..', '', $namespace);
                    $namespace = str_replace('\\', '/', $namespace);
                    $ns[] = $namespace;

                }
            }
        }
        if ($ns) {
            $ns = array_unique($ns);
        }
        return $ns;
    }


    function _mw_get_language_file_content_core()
    {
        global $mw_language_content;
        $lang = current_lang();
        if (isset($mw_language_content[$lang]) and !empty($mw_language_content[$lang])) {
            return $mw_language_content[$lang];
        }
        if (!isset($mw_language_content[$lang])) {
            $mw_language_content[$lang] = array();
        }

        $lang_file = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';
        $lang_file = normalize_path($lang_file, false);
        $lang_file2 = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';
        $lang_file3 = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . 'en.json';

        if (is_file($lang_file2)) {
            $language_str = file_get_contents($lang_file2);
            $language = json_decode($language_str, true);
            if (isset($language) and is_array($language)) {
                foreach ($language as $k => $v) {
                    // if (isset($mw_language_content[$lang][$k]) == false) {
                    $mw_language_content[$lang][$k] = $v;
                    // }
                }
            }
        }
        if (is_file($lang_file)) {
            $language_str = file_get_contents($lang_file);
            $language = json_decode($language_str, true);
            if (isset($language) and is_array($language)) {
                foreach ($language as $k => $v) {
                    if (isset($mw_language_content[$lang][$k]) == false) {
                        $mw_language_content[$lang][$k] = $v;
                    }
                }
            }
        }
        if (is_file($lang_file3)) {
            $language_str = file_get_contents($lang_file3);
            $language = json_decode($language_str, true);
            if (isset($language) and is_array($language)) {
                foreach ($language as $k => $v) {
                    if (isset($mw_language_content[$lang][$k]) == false) {
                        $mw_language_content[$lang][$k] = $v;
                    }
                }
            }
        }

        return $mw_language_content[$lang];
    }

    function _mw_get_language_file_content_namespaced($namespace)
    {
        if ($namespace == false) {
            return false;
        }
        $lang = current_lang();
        global $mw_language_content_namespace;
        $namespace = trim($namespace);
        $namespace = str_replace(' ', '', $namespace);
        $namespace = str_replace('..', '', $namespace);
        $namespace = str_replace('\\', '/', $namespace);
        if (isset($mw_language_content_namespace[$lang][$namespace]) and !empty($mw_language_content_namespace[$lang][$namespace])) {
            return $mw_language_content_namespace[$lang][$namespace];
        }


        if (!isset($mw_language_content_namespace[$lang])) {
            $mw_language_content_namespace[$lang] = array();
        }


//    $lang_file = userfiles_path() . $namespace . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.json';
//    $lang_file = normalize_path($lang_file, false);

        $lang_file2 = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $lang . '.json';
        $lang_file2 = normalize_path($lang_file2, false);

        //$lang_file3 = userfiles_path() . $namespace . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en.json';
        $lang_file3 = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . 'en.json';
        $lang_file3 = normalize_path($lang_file3, false);

        if (!isset($mw_language_content_namespace[$lang][$namespace])) {
            $mw_language_content_namespace[$lang][$namespace] = array();
        }
        if (is_file($lang_file2)) {
            $language_str = file_get_contents($lang_file2);
            $language = json_decode($language_str, true);
            if (isset($language) and is_array($language)) {
                foreach ($language as $k => $v) {
                    if (isset($mw_language_content_namespace[$lang][$namespace][$k]) == false) {
                        $mw_language_content_namespace[$lang][$namespace][$k] = $v;
                    }
                }
            }
        }

//    if (is_file($lang_file)) {
//        $language_str = file_get_contents($lang_file);
//        $language = json_decode($language_str, true);
//
//        if (isset($language) and is_array($language)) {
//            foreach ($language as $k => $v) {
//                if (isset($mw_language_content_namespace[$namespace][$k]) == false) {
//                    $mw_language_content_namespace[$namespace][$k] = $v;
//                }
//            }
//        }
//    }
        if (is_file($lang_file3)) {
            $language_str = file_get_contents($lang_file3);
            $language = json_decode($language_str, true);
            if (isset($language) and is_array($language)) {
                foreach ($language as $k => $v) {
                    if (isset($mw_language_content_namespace[$lang][$namespace][$k]) == false) {
                        $mw_language_content_namespace[$lang][$namespace][$k] = $v;
                    }
                }
            }
        }

        return $mw_language_content_namespace[$lang][$namespace];
    }


    function lang_is_rtl($lang = false)
    {
        if (!$lang) {
            $lang = app()->getLocale();
        }
        /*
        ar	Arabic	rtl	العربية
        arc	Aramaic	rtl	ܣܘܪܬ
        dv	Divehi	rtl	ދިވެހިބަސް
        far	Farsi	rtl	فارسی
        ha	Hausa	rtl	هَوُسَ
        he	Hebrew	rtl	עברית
        khw	Khowar	rtl	کھوار
        ks	Kashmiri	rtl	कश्मीरी / كشميري
        ku	Kurdish	rtl	Kurdî / كوردی
        ps	Pashto	rtl	پښتو
        ur	Urdu	rtl	اردو
        yi	Yiddish	rtl	ייִדיש
        */

        return LanguageHelper::isRTL($lang);

//        $rtl_langs = array('ar', 'arc', 'dv', 'far', 'khw', 'ks', 'ps', 'ur', 'yi');
//        if ($lang and in_array($lang, $rtl_langs)) {
//            return true;
//        }
    }


    /**
     * Saves your custom language translation.
     *
     * @internal its used via ajax in the admin panel under Settings->Language
     */


    function save_language_file_content($data)
    {
        // Decode json from post
        if (isset($data['lines']) && is_string($data['lines'])) {
            $jsonDecode = json_decode($data['lines'], true);
            if ($jsonDecode && is_array($jsonDecode)) {
                $readyData = array();
                foreach ($jsonDecode as $dataExplode) {
                    $readyData[$dataExplode['name']] = $dataExplode['value'];
                }
                $data = $readyData;
            }
        }

        if (is_admin() == true) {
            if (isset($data['unicode_temp_remove'])) {
                unset($data['unicode_temp_remove']);
            }
            $ns = false;
            if (isset($data['___namespace'])) {
                $ns = $data['___namespace'];
                unset($data['___namespace']);
            }

            $lang = get_option('language', 'website');
            if (isset($data['___lang'])) {
                $lang = $data['___lang'];
                unset($data['___lang']);
            }

            if (!$lang) {
                $lang = current_lang();
            }
            if (!$lang) {
                return;
            }
            $lang = str_replace('.', '', $lang);

            //$lang = current_lang();

            // $cust_dir = $lang_file = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;

            $cust_dir = userfiles_path() . 'language' . DIRECTORY_SEPARATOR;

            if ($ns) {
                $ns_dir = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $ns . DIRECTORY_SEPARATOR;
                if (is_dir($ns_dir)) {
                    $cust_dir = $ns_dir;
                }
            }

            if (!is_dir($cust_dir)) {
                mkdir_recursive($cust_dir);
            }

            $mw_language_content = $data;

            $lang_file = $cust_dir . $lang . '.json';

            if (is_array($mw_language_content)) {
                // $mw_language_content = array_unique($mw_language_content);
                $lang_file_str = json_encode($mw_language_content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

                if (is_admin() == true) {
                    file_put_contents($lang_file, $lang_file_str);
                }
            }

            return array('success' => 'Language file [' . $lang . '] is updated');
        }
    }

    function get_all_lang_codes()
    {
        return \Symfony\Component\Intl\Locales::getNames();
    }

    private function ___get_all_lang_codes()
    {
        $langs = array(
            //  'Abkhazian' => 'AB',
            // 'Afar' => 'AA',
            'Afrikaans' => 'AF',
            'Albanian' => 'SQ',
            'Amharic' => 'AM',
            'Arabic' => 'AR',
            'Armenian' => 'HY',
            'Assamese' => 'AS',
            'Aymara' => 'AY',
            'Azerbaijani' => 'AZ',
            'Bashkir' => 'BA',
            'Basque' => 'EU',
            'Bengali, Bangla' => 'BN',
            'Bhutani' => 'DZ',
            'Bihari' => 'BH',
            'Bislama' => 'BI',
            'Breton' => 'BR',
            'Bulgarian' => 'BG',
            'Burmese' => 'MY',
            'Byelorussian' => 'BE',
            'Cambodian' => 'KM',
            'Catalan' => 'CA',
            'Chinese' => 'ZH_CN',
            'Corsican' => 'CO',
            'Croatian' => 'HR',
            'Czech' => 'CS',
            'Danish' => 'DA',
            'Dutch' => 'NL',
            'English, American' => 'EN',
            'English, United Kingdom' => 'EN_UK',
            'Esperanto' => 'EO',
            'Estonian' => 'ET',
            'Faeroese' => 'FO',
            'Fiji' => 'FJ',
            'Finnish' => 'FI',
            'French' => 'FR',
            'Frisian' => 'FY',
            'Gaelic (Scots Gaelic)' => 'GD',
            'Galician' => 'GL',
            'Georgian' => 'KA',
            'German' => 'DE',
            'Greek' => 'EL',
            'Greenlandic' => 'KL',
            'Guarani' => 'GN',
            'Gujarati' => 'GU',
            'Hausa' => 'HA',
            'Hebrew' => 'IW',
            'Hindi' => 'HI',
            'Hungarian' => 'HU',
            'Icelandic' => 'IS',
            'Indonesian' => 'IN',
            'Interlingua' => 'IA',
            'Interlingue' => 'IE',
            'Inupiak' => 'IK',
            'Irish' => 'GA',
            'Italian' => 'IT',
            'Japanese' => 'JA',
            'Javanese' => 'JW',
            'Kannada' => 'KN',
            'Kashmiri' => 'KS',
            'Kazakh' => 'KK',
            'Kinyarwanda' => 'RW',
            'Kirghiz' => 'KY',
            'Kirundi' => 'RN',
            'Korean' => 'KO',
            'Kurdish' => 'KU',
            'Laothian' => 'LO',
            'Latin' => 'LA',
            'Latvian, Lettish' => 'LV',
            'Lingala' => 'LN',
            'Lithuanian' => 'LT',
            'Macedonian' => 'MK',
            'Malagasy' => 'MG',
            'Malay' => 'MS',
            'Malayalam' => 'ML',
            'Maltese' => 'MT',
            'Maori' => 'MI',
            'Marathi' => 'MR',
            'Moldavian' => 'MO',
            'Mongolian' => 'MN',
            'Nauru' => 'NA',
            'Nepali' => 'NE',
            'Norwegian' => 'NO',
            'Occitan' => 'OC',
            'Oriya' => 'OR',
            'Oromo, Afan' => 'OM',
            'Pashto, Pushto' => 'PS',
            'Persian' => 'FA',
            'Polish' => 'PL',
            'Portuguese' => 'PT',
            'Punjabi' => 'PA',
            'Quechua' => 'QU',
            'Rhaeto-Romance' => 'RM',
            'Romanian' => 'RO',
            'Russian' => 'RU',
            'Samoan' => 'SM',
            'Sangro' => 'SG',
            'Sanskrit' => 'SA',
            'Serbian' => 'SR',
            'Serbo-Croatian' => 'SH',
            'Sesotho' => 'ST',
            'Setswana' => 'TN',
            'Shona' => 'SN',
            'Sindhi' => 'SD',
            'Singhalese' => 'SI',
            'Siswati' => 'SS',
            'Slovak' => 'SK',
            'Slovenian' => 'SL',
            'Somali' => 'SO',
            'Spanish' => 'ES',
            'Sudanese' => 'SU',
            'Swahili' => 'SW',
            'Swedish' => 'SV',
            'Tagalog' => 'TL',
            'Tajik' => 'TG',
            'Tamil' => 'TA',
            'Tatar' => 'TT',
            'Tegulu' => 'TE',
            'Thai' => 'TH',
            'Tibetan' => 'BO',
            'Tigrinya' => 'TI',
            'Tonga' => 'TO',
            'Tsonga' => 'TS',
            'Turkish' => 'TR',
            'Turkmen' => 'TK',
            'Twi' => 'TW',
            'Ukrainian' => 'UK',
            'Urdu' => 'UR',
            'Uzbek' => 'UZ',
            'Vietnamese' => 'VI',
            'Volapuk' => 'VO',
            'Welsh' => 'CY',
            'Wolof' => 'WO',
            'Xhosa' => 'XH',
            'Yiddish' => 'JI',
            'Yoruba' => 'YO',
            'Zulu' => 'ZU'
        );


        $langs = array_flip($langs);
        $langs = array_change_key_case($langs);
        return $langs;
    }

    function get_all_locales()
    {
        return array(
            'aa_DJ' => 'Afar (Djibouti)',
            'aa_ER' => 'Afar (Eritrea)',
            'aa_ET' => 'Afar (Ethiopia)',
            'af_ZA' => 'Afrikaans (South Africa)',
            'sq_AL' => 'Albanian (Albania)',
            'sq_MK' => 'Albanian (Macedonia)',
            'am_ET' => 'Amharic (Ethiopia)',
            'ar_DZ' => 'Arabic (Algeria)',
            'ar_BH' => 'Arabic (Bahrain)',
            'ar_EG' => 'Arabic (Egypt)',
            'ar_IN' => 'Arabic (India)',
            'ar_IQ' => 'Arabic (Iraq)',
            'ar_JO' => 'Arabic (Jordan)',
            'ar_KW' => 'Arabic (Kuwait)',
            'ar_LB' => 'Arabic (Lebanon)',
            'ar_LY' => 'Arabic (Libya)',
            'ar_MA' => 'Arabic (Morocco)',
            'ar_OM' => 'Arabic (Oman)',
            'ar_QA' => 'Arabic (Qatar)',
            'ar_SA' => 'Arabic (Saudi Arabia)',
            'ar_SD' => 'Arabic (Sudan)',
            'ar_SY' => 'Arabic (Syria)',
            'ar_TN' => 'Arabic (Tunisia)',
            'ar_AE' => 'Arabic (United Arab Emirates)',
            'ar_YE' => 'Arabic (Yemen)',
            'an_ES' => 'Aragonese (Spain)',
            'hy_AM' => 'Armenian (Armenia)',
            'as_IN' => 'Assamese (India)',
            'ast_ES' => 'Asturian (Spain)',
            'az_AZ' => 'Azerbaijani (Azerbaijan)',
            'az_TR' => 'Azerbaijani (Turkey)',
            'eu_FR' => 'Basque (France)',
            'eu_ES' => 'Basque (Spain)',
            'be_BY' => 'Belarusian (Belarus)',
            'bem_ZM' => 'Bemba (Zambia)',
            'bn_BD' => 'Bengali (Bangladesh)',
            'bn_IN' => 'Bengali (India)',
            'ber_DZ' => 'Berber (Algeria)',
            'ber_MA' => 'Berber (Morocco)',
            'byn_ER' => 'Blin (Eritrea)',
            'bs_BA' => 'Bosnian (Bosnia and Herzegovina)',
            'br_FR' => 'Breton (France)',
            'bg_BG' => 'Bulgarian (Bulgaria)',
            'my_MM' => 'Burmese (Myanmar [Burma])',
            'ca_AD' => 'Catalan (Andorra)',
            'ca_FR' => 'Catalan (France)',
            'ca_IT' => 'Catalan (Italy)',
            'ca_ES' => 'Catalan (Spain)',
            'zh_CN' => 'Chinese (China)',
            'zh_HK' => 'Chinese (Hong Kong SAR China)',
            'zh_SG' => 'Chinese (Singapore)',
            'zh_TW' => 'Chinese (Taiwan)',
            'cv_RU' => 'Chuvash (Russia)',
            'kw_GB' => 'Cornish (United Kingdom)',
            'crh_UA' => 'Crimean Turkish (Ukraine)',
            'hr_HR' => 'Croatian (Croatia)',
            'cs_CZ' => 'Czech (Czech Republic)',
            'da_DK' => 'Danish (Denmark)',
            'dv_MV' => 'Divehi (Maldives)',
            'nl_AW' => 'Dutch (Aruba)',
            'nl_BE' => 'Dutch (Belgium)',
            'nl_NL' => 'Dutch (Netherlands)',
            'dz_BT' => 'Dzongkha (Bhutan)',
            'en_AG' => 'English (Antigua and Barbuda)',
            'en_AU' => 'English (Australia)',
            'en_BW' => 'English (Botswana)',
            'en_CA' => 'English (Canada)',
            'en_DK' => 'English (Denmark)',
            'en_HK' => 'English (Hong Kong SAR China)',
            'en_IN' => 'English (India)',
            'en_IE' => 'English (Ireland)',
            'en_NZ' => 'English (New Zealand)',
            'en_NG' => 'English (Nigeria)',
            'en_PH' => 'English (Philippines)',
            'en_SG' => 'English (Singapore)',
            'en_ZA' => 'English (South Africa)',
            'en_GB' => 'English (United Kingdom)',
            'en_US' => 'English (United States)',
            'en_ZM' => 'English (Zambia)',
            'en_ZW' => 'English (Zimbabwe)',
            'eo' => 'Esperanto',
            'et_EE' => 'Estonian (Estonia)',
            'fo_FO' => 'Faroese (Faroe Islands)',
            'fil_PH' => 'Filipino (Philippines)',
            'fi_FI' => 'Finnish (Finland)',
            'fr_BE' => 'French (Belgium)',
            'fr_CA' => 'French (Canada)',
            'fr_FR' => 'French (France)',
            'fr_LU' => 'French (Luxembourg)',
            'fr_CH' => 'French (Switzerland)',
            'fur_IT' => 'Friulian (Italy)',
            'ff_SN' => 'Fulah (Senegal)',
            'gl_ES' => 'Galician (Spain)',
            'lg_UG' => 'Ganda (Uganda)',
            'gez_ER' => 'Geez (Eritrea)',
            'gez_ET' => 'Geez (Ethiopia)',
            'ka_GE' => 'Georgian (Georgia)',
            'de_AT' => 'German (Austria)',
            'de_BE' => 'German (Belgium)',
            'de_DE' => 'German (Germany)',
            'de_LI' => 'German (Liechtenstein)',
            'de_LU' => 'German (Luxembourg)',
            'de_CH' => 'German (Switzerland)',
            'el_CY' => 'Greek (Cyprus)',
            'el_GR' => 'Greek (Greece)',
            'gu_IN' => 'Gujarati (India)',
            'ht_HT' => 'Haitian (Haiti)',
            'ha_NG' => 'Hausa (Nigeria)',
            'iw_IL' => 'Hebrew (Israel)',
            'he_IL' => 'Hebrew (Israel)',
            'hi_IN' => 'Hindi (India)',
            'hu_HU' => 'Hungarian (Hungary)',
            'is_IS' => 'Icelandic (Iceland)',
            'ig_NG' => 'Igbo (Nigeria)',
            'id_ID' => 'Indonesian (Indonesia)',
            'ia' => 'Interlingua',
            'iu_CA' => 'Inuktitut (Canada)',
            'ik_CA' => 'Inupiaq (Canada)',
            'ga_IE' => 'Irish (Ireland)',
            'it_IT' => 'Italian (Italy)',
            'it_CH' => 'Italian (Switzerland)',
            'ja_JP' => 'Japanese (Japan)',
            'kl_GL' => 'Kalaallisut (Greenland)',
            'kn_IN' => 'Kannada (India)',
            'ks_IN' => 'Kashmiri (India)',
            'csb_PL' => 'Kashubian (Poland)',
            'kk_KZ' => 'Kazakh (Kazakhstan)',
            'km_KH' => 'Khmer (Cambodia)',
            'rw_RW' => 'Kinyarwanda (Rwanda)',
            'ky_KG' => 'Kirghiz (Kyrgyzstan)',
            'kok_IN' => 'Konkani (India)',
            'ko_KR' => 'Korean (South Korea)',
            'ku_TR' => 'Kurdish (Turkey)',
            'lo_LA' => 'Lao (Laos)',
            'lv_LV' => 'Latvian (Latvia)',
            'li_BE' => 'Limburgish (Belgium)',
            'li_NL' => 'Limburgish (Netherlands)',
            'lt_LT' => 'Lithuanian (Lithuania)',
            'nds_DE' => 'Low German (Germany)',
            'nds_NL' => 'Low German (Netherlands)',
            'mk_MK' => 'Macedonian (Macedonia)',
            'mai_IN' => 'Maithili (India)',
            'mg_MG' => 'Malagasy (Madagascar)',
            'ms_MY' => 'Malay (Malaysia)',
            'ml_IN' => 'Malayalam (India)',
            'mt_MT' => 'Maltese (Malta)',
            'gv_GB' => 'Manx (United Kingdom)',
            'mi_NZ' => 'Maori (New Zealand)',
            'mr_IN' => 'Marathi (India)',
            'mn_MN' => 'Mongolian (Mongolia)',
            'ne_NP' => 'Nepali (Nepal)',
            'se_NO' => 'Northern Sami (Norway)',
            'nso_ZA' => 'Northern Sotho (South Africa)',
            'nb_NO' => 'Norwegian Bokmål (Norway)',
            'nn_NO' => 'Norwegian Nynorsk (Norway)',
            'oc_FR' => 'Occitan (France)',
            'or_IN' => 'Oriya (India)',
            'om_ET' => 'Oromo (Ethiopia)',
            'om_KE' => 'Oromo (Kenya)',
            'os_RU' => 'Ossetic (Russia)',
            'pap_AN' => 'Papiamento (Netherlands Antilles)',
            'ps_AF' => 'Pashto (Afghanistan)',
            'fa_IR' => 'Persian (Iran)',
            'pl_PL' => 'Polish (Poland)',
            'pt_BR' => 'Portuguese (Brazil)',
            'pt_PT' => 'Portuguese (Portugal)',
            'pa_IN' => 'Punjabi (India)',
            'pa_PK' => 'Punjabi (Pakistan)',
            'ro_RO' => 'Romanian (Romania)',
            'ru_RU' => 'Russian (Russia)',
            'ru_UA' => 'Russian (Ukraine)',
            'sa_IN' => 'Sanskrit (India)',
            'sc_IT' => 'Sardinian (Italy)',
            'gd_GB' => 'Scottish Gaelic (United Kingdom)',
            'sr_ME' => 'Serbian (Montenegro)',
            'sr_RS' => 'Serbian (Serbia)',
            'sid_ET' => 'Sidamo (Ethiopia)',
            'sd_IN' => 'Sindhi (India)',
            'si_LK' => 'Sinhala (Sri Lanka)',
            'sk_SK' => 'Slovak (Slovakia)',
            'sl_SI' => 'Slovenian (Slovenia)',
            'so_DJ' => 'Somali (Djibouti)',
            'so_ET' => 'Somali (Ethiopia)',
            'so_KE' => 'Somali (Kenya)',
            'so_SO' => 'Somali (Somalia)',
            'nr_ZA' => 'South Ndebele (South Africa)',
            'st_ZA' => 'Southern Sotho (South Africa)',
            'es_AR' => 'Spanish (Argentina)',
            'es_BO' => 'Spanish (Bolivia)',
            'es_CL' => 'Spanish (Chile)',
            'es_CO' => 'Spanish (Colombia)',
            'es_CR' => 'Spanish (Costa Rica)',
            'es_DO' => 'Spanish (Dominican Republic)',
            'es_EC' => 'Spanish (Ecuador)',
            'es_SV' => 'Spanish (El Salvador)',
            'es_GT' => 'Spanish (Guatemala)',
            'es_HN' => 'Spanish (Honduras)',
            'es_MX' => 'Spanish (Mexico)',
            'es_NI' => 'Spanish (Nicaragua)',
            'es_PA' => 'Spanish (Panama)',
            'es_PY' => 'Spanish (Paraguay)',
            'es_PE' => 'Spanish (Peru)',
            'es_ES' => 'Spanish (Spain)',
            'es_US' => 'Spanish (United States)',
            'es_UY' => 'Spanish (Uruguay)',
            'es_VE' => 'Spanish (Venezuela)',
            'sw_KE' => 'Swahili (Kenya)',
            'sw_TZ' => 'Swahili (Tanzania)',
            'ss_ZA' => 'Swati (South Africa)',
            'sv_FI' => 'Swedish (Finland)',
            'sv_SE' => 'Swedish (Sweden)',
            'tl_PH' => 'Tagalog (Philippines)',
            'tg_TJ' => 'Tajik (Tajikistan)',
            'ta_IN' => 'Tamil (India)',
            'tt_RU' => 'Tatar (Russia)',
            'te_IN' => 'Telugu (India)',
            'th_TH' => 'Thai (Thailand)',
            'bo_CN' => 'Tibetan (China)',
            'bo_IN' => 'Tibetan (India)',
            'tig_ER' => 'Tigre (Eritrea)',
            'ti_ER' => 'Tigrinya (Eritrea)',
            'ti_ET' => 'Tigrinya (Ethiopia)',
            'ts_ZA' => 'Tsonga (South Africa)',
            'tn_ZA' => 'Tswana (South Africa)',
            'tr_CY' => 'Turkish (Cyprus)',
            'tr_TR' => 'Turkish (Turkey)',
            'tk_TM' => 'Turkmen (Turkmenistan)',
            'ug_CN' => 'Uighur (China)',
            'uk_UA' => 'Ukrainian (Ukraine)',
            'hsb_DE' => 'Upper Sorbian (Germany)',
            'ur_PK' => 'Urdu (Pakistan)',
            'uz_UZ' => 'Uzbek (Uzbekistan)',
            've_ZA' => 'Venda (South Africa)',
            'vi_VN' => 'Vietnamese (Vietnam)',
            'wa_BE' => 'Walloon (Belgium)',
            'cy_GB' => 'Welsh (United Kingdom)',
            'fy_DE' => 'Western Frisian (Germany)',
            'fy_NL' => 'Western Frisian (Netherlands)',
            'wo_SN' => 'Wolof (Senegal)',
            'xh_ZA' => 'Xhosa (South Africa)',
            'yi_US' => 'Yiddish (United States)',
            'yo_NG' => 'Yoruba (Nigeria)',
            'zu_ZA' => 'Zulu (South Africa)',
        );
    }


    /**
     * Get all available languages as array.
     *
     * To set user language you must create cookie named "lang"
     *
     * @return array The languages array
     *
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
        foreach (glob($lang_dir . '*.json') as $filename) {
            $item = basename($filename);
            $item = no_ext($item);
            $mw_all_langs[] = $item;
        }

        return $mw_all_langs;
    }

//    function is_supported($lang)
//    {
//        //@todo
//
//        $all_langs = $this->get_available_languages();
//        $all_locales = $this->get_all_locales();
//
//    }
    private function _rsearch($folder, $pattern)
    {

        $files = array();
        $iti = new \RecursiveDirectoryIterator($folder);
        foreach (new \RecursiveIteratorIterator($iti) as $file) {

            if (strpos($file, $pattern) !== false) {
                $files[] = $file->getPathname();
                // return $file;
            }
        }
        return $files;
    }

}


