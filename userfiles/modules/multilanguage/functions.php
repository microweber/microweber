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

event_bind('mw.crud.content.get', function($posts) {
    if (isset($posts[0])) {
        foreach ($posts as &$post) {
            if (isset($post['id']) && isset($post['title'])) {

                $content = translate_content($post['id']);

                if (!empty($content['title'])) {
                    $post['title'] = $content['title'];
                }
            }
        }
        return $posts;
    }

});

event_bind('mw.content.save_edit', function ($save) {

    if (isset($save['field']) && isset($save['rel_type']) && $save['rel_type'] == 'content' && isset($save['value'])) {

        var_dump($save);
        die();

        $locale = get_current_locale();

        $save_translations = array();
        $save_translations['field'] = $save['field'];
        $save_translations['rel_id'] = $save['rel_id'];
        $save_translations['rel_type'] = $save['rel_type'];
        $save_translations['locale'] = $locale;
        $save_translations['value'] = trim($save['value']);

        $find_translations = translate_content_fields($save['field'], $save['rel_type'], $save['rel_id'], $locale);
        if ($find_translations) {
            $save_translations['id'] = $find_translations['id'];
        }

        $save_translations['allow_html'] = 1;
        $save_translations['allow_scripts'] = 1;

        db_save('content_fields_translations', $save_translations);

    }

});

event_bind('mw.database.extended_save', function ($save) {

    if (isset($save['table']) && $save['table'] == 'content' && isset($save['title'])) {

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

