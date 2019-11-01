<?php

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

function translate_content($content_id, $locale = false) {
    if (!$locale) {
        $locale = get_current_locale();
    }
    return db_get('content_translations', array(
        'locale'=> $locale,
        'content_id' => $content_id,
        'single'=>1
    ));
}

event_bind('mw.crud.content.get', function($posts) {
    if (isset($posts[0])) {
        foreach ($posts as &$post) {
            if (isset($post['id']) && isset($post['content_type']) && $post['content_type'] == 'post') {

                $content = translate_content($post['id']);

                if (!empty($content['title'])) {
                    $post['title'] = $content['title'];
                }
            }
        }
        return $posts;
    }

});

event_bind('content.manager.after.save', function ($save) {
    if (isset($save['content_type']) && $save['content_type'] == 'post') {

        $locale = get_current_locale();

        $save_translations = array();
        $save_translations['content_id'] = $save['id'];
        $save_translations['locale'] = $locale;
       // $save_translations['url'] = $save['url'];
        $save_translations['title'] = $save['title'];
        $save_translations['description'] = $save['description'];
        $save_translations['content_meta_title'] = $save['content_meta_title'];
        $save_translations['content_meta_keywords'] = $save['content_meta_keywords'];

        $find_translations = translate_content($save['id'], $locale);
        if ($find_translations) {
            $save_translations['id'] = $find_translations['id'];
        }

        db_save('content_translations', $save_translations);

    }
});

