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

// $this->app->event_manager->trigger('category.after.get', $data_to_save);

event_bind('option.after.get', function($item) {
    if (isset($item['option_key']) && $item['option_key'] == 'language') {
        return $item;
    }
    if(!empty($item)) {
        $translate = new TranslateOption();
        $translated = $translate->getTranslate($item);
        return $translated;
    }
});

event_bind('option.before.save', function($save) {

    /*if (isset($save['option_key']) && $save['option_key'] == 'language') {
        return false;
    }

    $translate = new TranslateOption();
    $currentLocale = $translate->getCurrentLocale();

    // Save old option to translate table
    $defaultLocale = 'en';
    if ($currentLocale !== $defaultLocale) {
        $option = get_option($save['option_key'], $save['option_group']);
        $oldOption = $save;
        $oldOption['option_value'] = $option;

        $translate->setLocale($defaultLocale);
        $translate->saveOrUpdate($oldOption);
    }*/


});

event_bind('option.after.save', function($save) {
    if (isset($save['option_key']) && $save['option_key'] == 'language') {
        return false;
    }
    if (isset($save['id']) && isset($save['option_value'])) {
        $translate = new TranslateOption();
        $translate->saveOrUpdate($save);
    }
});

event_bind('category.after.get', function($item) {
    if(!empty($item)) {
        $translate = new TranslateCategory();
        $translated = $translate->getTranslate($item);
        return $translated;
    }
});

event_bind('category.after.save', function($save) {
    if (isset($save['rel_type']) && isset($save['rel_id'])) {
        $translate = new TranslateCategory();
        $translate->saveOrUpdate($save);
    }
});

event_bind('menu.after.get_item', function($item) {
    if(!empty($item)) {
        $translate = new TranslateMenu();
        $translated = $translate->getTranslate($item);
        return $translated;
    }
});

event_bind('menu.after.save', function($save) {
    if (isset($save['id']) && isset($save['title'])) {
        $translate = new TranslateMenu();
        $translate->saveOrUpdate($save);
    }
});

event_bind('content_fields.after.save', function($save) {
    if (isset($save['rel_type']) && isset($save['rel_id'])) {
        $translate = new TranslateContentFields();
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
