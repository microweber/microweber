<?php


$mw_language_content_saved = false;
$mw_new_language_entires = array();
$mw_new_language_entires_ns = array();
function ewchar_to_utf8($matches)
{
    $ewchar = $matches[1];
    $binwchar = hexdec($ewchar);
    $wchar = chr(($binwchar >> 8) & 0xFF).chr(($binwchar) & 0xFF);

    return iconv('unicodebig', 'utf-8', $wchar);
}

function special_unicode_to_utf8($str)
{
    return preg_replace_callback("/\\\u([[:xdigit:]]{4})/i", 'ewchar_to_utf8', $str);
}

function __store_lang_file_ns()
{
    global $mw_new_language_entires_ns;
    global $mw_new_language_entires_ns;
    $lang = current_lang();
    if (!empty($mw_new_language_entires_ns)) {
        foreach ($mw_new_language_entires_ns as $k => $v) {
            $namespace = $k;

            $lang_file = userfiles_path().$namespace.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.$lang.'.json';
            $lang_file = normalize_path($lang_file, false);

            $lang_file2 = userfiles_path().'language'.DIRECTORY_SEPARATOR.$namespace.DIRECTORY_SEPARATOR.$lang.'.json';
            $lang_file2 = normalize_path($lang_file2, false);

            $lang_file3 = userfiles_path().$namespace.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.'en.json';
            $lang_file3 = normalize_path($lang_file3, false);

            $lang_file_save = false;
            $existing = $mw_new_language_entires_ns[$k];
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
                if (!is_file($lang_file)) {
                    @touch($lang_file);
                }
                if (!is_file($lang_file2)) {
                    @touch($lang_file2);
                }
                if (!is_file($lang_file3)) {
                    @touch($lang_file3);
                }

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

    $lang_file = userfiles_path().'language'.DIRECTORY_SEPARATOR.$lang.'.json';
    if (!is_array($mw_language_content) or empty($mw_language_content)) {
        $lang_file_str = json_encode($mw_new_language_entires, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        @file_put_contents($lang_file, $lang_file_str);
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

        $lang_file_str = json_encode($mw_language_content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
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
                            @touch($lang_file);
                        }

                        if (function_exists('iconv')) {
                            $lang_file_str = special_unicode_to_utf8($lang_file_str);
                        }

                        $lang_file_str = str_replace('","', '",'."\n".'"', $lang_file_str);
                        if (is_writable($lang_file) and is_string($lang_file_str) and $lang_file_str != '') {
                            @file_put_contents($lang_file, $lang_file_str);
                        }
                    }
                }
            }
        }
    }
}
api_expose('set_current_lang');
function set_current_lang($lang = 'en')
{
    $lang = str_replace('.', '', $lang);
    $lang = str_replace(DIRECTORY_SEPARATOR, '', $lang);
    $lang = filter_var($lang, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    App::setLocale($lang);
}

/**
 * Get the current language of the site.
 *
 * @example
 * <code>
 *  $current_lang = current_lang();
 *  print $current_lang;
 * </code>
 */
function current_lang()
{
    $app_locale = App::getLocale();
    if (isset($_COOKIE['lang']) and $_COOKIE['lang'] != false) {
        $lang = $_COOKIE['lang'];
        if ($lang != $app_locale) {
            set_current_lang($lang);
            $app_locale = App::getLocale();
        }
    }

    return $app_locale;
}

function _lang($title, $namespace = false)
{
    echo lang($title, $namespace);
}

function lang($title, $namespace = false)
{
    static $lang_file;
    global $mw_language_content;
    global $mw_new_language_entires_ns;
    $k = $title;
    //$k1 = strip_tags($k);
    $k1 = 'key'.crc32($k);
    //$k1 = str_replace(array(',',' ','_','.',"\n","\r","\t",'"',"'",'<','>',':',';','!','`'),'-',strtolower($k1));
   // $k1 = str_replace('---','-',$k1);
   // $k1 = str_replace('--','-',$k1);
    //$k1 = preg_replace('/-+/', '-', $k1);

    //$k1 = trim($k1,'-');

 //   $k1 = mw()->url_manager->slug($k);

    $lang = current_lang();

    $mw_language_content_file = get_language_file_content($namespace);

    if (isset($mw_language_content_file[$k1]) == false) {
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

            if (!defined('MW_LANG_STORE_ON_EXIT_EVENT_BINDED_NS')) {
                define('MW_LANG_STORE_ON_EXIT_EVENT_BINDED_NS', 1);
                $scheduler = new \Microweber\Providers\Event();
                $scheduler->registerShutdownEvent('__store_lang_file_ns');
            }
        }

        return $k;
    } else {
        return $mw_language_content_file[$k1];
    }
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
function _e($k, $to_return = false)
{
    static $lang_file;
    global $mw_new_language_entires;

    $k1 = mw()->url_manager->slug($k);

    $lang = current_lang();

    $mw_language_content = get_language_file_content();

    if (isset($mw_language_content[$k1]) == false) {
        //if (is_admin() == true) {
        $k2 = ($k);
        $mw_new_language_entires[$k1] = $k2;
        $mw_language_content[$k1] = $k2;
        if (!defined('MW_LANG_STORE_ON_EXIT_EVENT_BINDED')) {
            define('MW_LANG_STORE_ON_EXIT_EVENT_BINDED', 1);
            $scheduler = new \Microweber\Providers\Event();
            // schedule a global scope function:
            $scheduler->registerShutdownEvent('__store_lang_file');
        }

        //}
        if ($to_return == true) {
            return $k;
        }
        echo $k;
    } else {
        if ($to_return == true) {
            return $mw_language_content[$k1];
        }
        echo $mw_language_content[$k1];
    }
}

api_expose('send_lang_form_to_microweber');
/**
 * Send your language translation to Microweber.
 *
 * @internal its used via ajax in the admin panel under Settings->Language
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
 * Saves your custom language translation.
 *
 * @internal its used via ajax in the admin panel under Settings->Language
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

        $cust_dir = userfiles_path().'language'.DIRECTORY_SEPARATOR;

        if (!is_dir($cust_dir)) {
            mkdir_recursive($cust_dir);
        }

        $mw_language_content = $data;

        $lang_file = $cust_dir.$lang.'.json';

        if (is_array($mw_language_content)) {
            $mw_language_content = array_unique($mw_language_content);
            $lang_file_str = json_encode($mw_language_content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            if (is_admin() == true) {
                file_put_contents($lang_file, $lang_file_str);
            }
        }

        return array('success' => 'Language file ['.$lang.'] is updated');
    }
}

$mw_language_content = array();
$mw_language_content_namespace = array();

/**
 * Gets all the language file contents.
 *
 * @internal its used via ajax in the admin panel under Settings->Language
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
    $lang_file = mw_includes_path().'language'.DIRECTORY_SEPARATOR.$lang.'.json';
    $lang_file = normalize_path($lang_file, false);
    $lang_file2 = userfiles_path().'language'.DIRECTORY_SEPARATOR.$lang.'.json';
    $lang_file3 = mw_includes_path().'language'.DIRECTORY_SEPARATOR.'en.json';

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

    $lang_file = userfiles_path().$namespace.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.$lang.'.json';
    $lang_file = normalize_path($lang_file, false);

    $lang_file2 = userfiles_path().'language'.DIRECTORY_SEPARATOR.$namespace.DIRECTORY_SEPARATOR.$lang.'.json';
    $lang_file2 = normalize_path($lang_file2, false);

    $lang_file3 = userfiles_path().$namespace.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.'en.json';
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

    $lang_dir = mw_includes_path().'language'.DIRECTORY_SEPARATOR;

    $files = array();
    foreach (glob($lang_dir.'*.json') as $filename) {
        $item = basename($filename);
        $item = no_ext($item);
        $mw_all_langs[] = $item;
    }

    return $mw_all_langs;
}

function _mw_get_locales()
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
 * Shows a section of the help file.
 *
 * @internal its used on the help in the admin
 */
function show_help($section = 'main')
{
    $lang = current_lang();

    $lang = str_replace('..', '', $lang);
    if (trim($lang) == '') {
        $lang = 'en';
    }

    $lang_file = mw_includes_path().'help'.DIRECTORY_SEPARATOR.$lang.'.php';
    $lang_file_en = mw_includes_path().'help'.DIRECTORY_SEPARATOR.$lang.'.php';
    $lang_file = normalize_path($lang_file, false);

    if (is_file($lang_file)) {
        include $lang_file;
    } elseif (is_file($lang_file_en)) {
        return $lang_file_en;
    }
}
