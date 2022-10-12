<?php


api_expose('set_current_lang');
function set_current_lang($lang = 'en')
{

    return mw()->lang_helper->set_current_lang($lang);
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
    return mw()->lang_helper->current_lang();
}

function default_lang()
{
    return mw()->lang_helper->default_lang();
}

function current_lang_abbr()
{
    $lang = mw()->lang_helper->current_lang();
    $langExp = explode('_',$lang);

    if (isset($langExp[0])) {
        return $langExp[0];
    }

    return $lang;
}

function lang_attributes()
{
    return mw()->lang_helper->lang_attributes();
}

function _lang_is_rtl($lang = false)
{
    return mw()->lang_helper->lang_is_rtl($lang);
}


function app_name()
{
    return 'Microweber';
}

function _lang($key, $namespace = false, $return = false)
{
    if ($return) {
        return lang($key, $namespace);
    }

    print lang($key, $namespace);
}

function lang($key, $namespace = false)
{
    $group = '*';
    if (!$namespace) {
        $namespace = '*';
    }



    // replace control chars https://stackoverflow.com/a/10133237/731166
    $pairs = array(
        "\x03" => "",
        "\x05" => "",
        "\x0E" => "",
        "\x16" => "",
    );
    $key = strtr($key, $pairs);


    $namespace = url_title($namespace);
    $trans = trans($namespace . '::'.$group.'.'.$key);

    $trans = str_replace($namespace . '::'.$group.'.', '', $trans);
    $trans = trim($trans);

    return $trans;
}

function _read_trans_key($key)
{
    $key = str_replace('::','__punc__', $key);

    return $key;
}

function _output_trans_key($key) {

    $key = str_replace('__punc__','::', $key);

    return $key;
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
function _e($k, $to_return = false, $replace = [])
{
    $locale = mw()->lang_helper->current_lang();
    $trans = trans('*.'.$k, $replace, $locale);
    $trans = ltrim($trans, '*.');

    $trans = str_ireplace('{{app_name}}', 'Microweber', $trans);

    if ($to_return) {
        return $trans;
    }

    echo $trans;
}

/**
 * Prints a string in the current language for javascript.
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
function _ejs($k, $to_return = false, $replace = [])
{
    $trans = trans('*.'.$k, $replace);
    $trans = ltrim($trans, '*.');

    $trans = htmlspecialchars($trans, ENT_QUOTES);

    if ($to_return) {
        return $trans;
    }

    echo $trans;
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
    return mw()->lang_helper->get_available_languages();


}


/**
 * Saves the language file after page load.
 *
 * @internal
 */
function __store_lang_file_ns()
{
    return mw()->lang_helper->__store_lang_file_ns();
}

/**
 * Saves the language file after page load.
 *
 * @internal
 */
function __store_lang_file()
{
    return mw()->lang_helper->__store_lang_file();
}


/**
 * Send your language translation to Microweber.
 *
 * @internal its used via ajax in the admin panel under Settings->Language
 */

api_expose_admin('send_lang_form_to_microweber', function ($data) {
    if (is_admin() == true) {
        $lang = current_lang();
        $send = array();
        $send['function_name'] = __FUNCTION__;
        $send['language'] = $lang;
        $send['data'] = $data;
        return mw_send_anonymous_server_data($send);
    }
});


api_expose_admin('save_language_file_content', function ($data) {
    return mw()->lang_helper->save_language_file_content($data);
});

function get_flag_icon($locale) {
    return \MicroweberPackages\Translation\LanguageHelper::getLanguageFlag($locale);
}
