<?php
require_once 'TranslateMenu.php';
require_once 'TranslateContent.php';

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

    $data = array();
    $data['option_key'] = 'language';
    $data['option_group'] = 'website';
    $data['option_value'] = $locale;

    $save = save_option($data);

    return $save;
});

function get_current_locale()
{
    $locale = get_option('language', 'website');
    if (empty($locale)) {
        $locale = 'en';
    }
    return $locale;
}

event_bind('menu.after.save', function($save) {
    if (isset($save['id']) && isset($save['title'])) {
        $translate = new TranslateMenu();
        $translate->saveOrUpdate($save);
    }
});

event_bind('mw.content.save_edit', function ($data) {
    if (isset($data['rel_type']) && isset($data['rel_id'])) {

        $save = array();
        $save['id'] = $data['rel_id'];
        $save['content'] = $data['value'];

        $translate = new TranslateContent();
        $translate->saveOrUpdate($save);
    }
});

event_bind('mw.crud.content.get', function($posts) {
    if (isset($posts[0])) {
        foreach ($posts as &$post) {
            if (isset($post['id']) && isset($post['title'])) {

                $translate = new TranslateContent();
                $translated = $translate->getTranslate($post);

                if (!empty($translated)) {
                    foreach ($translated as $key => $value) {
                        $post[$key] = $value;
                    }
                }
            }
        }
        return $posts;
    }
});


event_bind('content.before.save', function ($save) {
    if (isset($save['id']) && isset($save['title'])) {
        $translate = new TranslateContent();
        $translate->saveOrUpdate($save);
    }
});
