<?php
require_once 'src/TranslateManager.php';

$translate = new TranslateManager();
$translate->run();

function get_flag_icon($locale) {
    if($locale == 'en'){
        $icon_key = 'us';
    }else{
        $icon_key = $locale;
    }
    return $icon_key;
}

api_expose('change_language', function(){

    $locale = $_POST['locale'];
    $langs  = mw()->lang_helper->get_all_lang_codes();

    if (!array_key_exists($locale, $langs)) {
        return false;
    }

    return mw()->lang_helper->set_current_lang($locale);

});