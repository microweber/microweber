<?php
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

/*
function translate_content($content_id, $locale = false) {
    if (!$locale) {
        $locale = get_current_locale();
    }
    return db_get('content_translations', array(
        'locale'=> $locale,
        'content_id' => $content_id,
        'single'=>1
    ));
}*/

/*
function translate_content_fields($field, $rel_type, $rel_id, $locale = false) {
    if (!$locale) {
        $locale = get_current_locale();
    }
    return db_get('content_fields_translations', array(
        'locale'=> $locale,
        'rel_type'=> $rel_type,
        'rel_id'=> $rel_id,
        'field' => $field,
        'single'=>1
    ));
}
*/
/*
event_bind('mw.crud.content.get', function($posts) {
    if (isset($posts[0])) {
        foreach ($posts as &$post) {
            if (isset($post['id']) && isset($post['title'])) {

                $translate = new TranslateContent();
                $translated = $translate->getTranslate($post);

                if (!empty($translated['title'])) {
                    $post['title'] = $translated['title'];
                }
            }
        }
        return $posts;
    }
});*/
/*
event_bind('content_fields.after.save', function($save) {

    var_dump($save);

});*/

event_bind('content.before.save', function ($save) {

    if (isset($save['id']) && isset($save['title'])) {
        $translate = new TranslateContent();
        $translate->saveOrUpdate($save);
    }
});
