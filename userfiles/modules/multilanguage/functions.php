<?php
require_once 'TranslateMenu.php';
require_once 'TranslateOption.php';
require_once 'TranslateCategory.php';
require_once 'TranslateContent.php';
require_once 'TranslateContentFields.php';

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


event_bind('mw.database.options.get', function($get) {

    if (is_array($get) && !empty($get)) {
        foreach ($get as &$item) {

            if (isset($item['option_key']) && $item['option_key'] == 'language') {
                continue;
            }

            $translate = new TranslateOption();
            $item = $translate->getTranslate($item);

        }
    }

    return $get;
});

event_bind('mw.database.options.save.params', function($save) {

    $currentLocale = mw()->lang_helper->current_lang();
    $defaultLocale = mw()->lang_helper->default_lang();

    if ($currentLocale != $defaultLocale) {

        if (isset($save['option_key']) && $save['option_key'] == 'language') {
            return false;
        }

        if (isset($save['id']) && isset($save['option_value'])) {
            $translate = new TranslateOption();
            $translate->saveOrUpdate($save);
        }

        if (isset($save['option_value'])) {
            unset($save['option_value']);
        }
    }

    return $save;
});