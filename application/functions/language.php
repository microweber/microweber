<?php

function _e($k) {
    static $c;
    static $lang_file;

    //$k = str_replace(' ', '-', $k);
    $k1 = URLify::filter(($k));

//	$k1 = url_title($k);
    if ($c === NULL) {
        if ($lang_file === NULL) {
            if (!isset($_SESSION) or session_get('lang') == 'en') {
                $lang = 'en';
            } else {
                $lang = session_get('lang');
            }
            $lang = str_replace('..', '', $lang);
            if (trim($lang) == '') {
                $lang = 'en';
            }
            $lang_file = APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.php';
            $lang_file = normalize_path($lang_file, false);
            //d($lang_file);
            if (is_file($lang_file)) {
                include ($lang_file);
            } else {
                if (is_admin() == true) {
                    $b = '<?php ' . "\n " . '$language' . " = array(); \n";
                    @file_put_contents($lang_file, $b);
                }
                $lang_file = APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en.php';
                $lang_file = normalize_path($lang_file, false);
                include ($lang_file);
            }
            //  d($lang_file);
            $c = $language;
        }
    } else {

    }
    if (isset($c[$k1]) == false) {
        if (is_admin() == true) {
            $k2 = addslashes($k);
            $b = '$language["' . $k1 . '"]' . "= '{$k2}' ; \n";
            @file_put_contents($lang_file, $b, FILE_APPEND);
        }

        print $k;
    } else {
        print $c[$k1];
    }
}

function set_language($lang = 'en') {
    session_set('lang', $lang);
    return $lang;
}
