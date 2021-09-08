<?php
/**
 * Author: Bozhidar Slaveykov
 */
use MicroweberPackages\Multilanguage\MultilanguagePermalinkManager;
//
//template_head(function () {
//
//    $currentLang = mw()->lang_helper->default_lang();
//
//    $content_link = content_link(CONTENT_ID);
//    $link = '<link rel="canonical" href="' . $content_link . '" />' . "\n";
//
//    $supportedLanguages = get_supported_languages();
//    foreach ($supportedLanguages as $locale) {
//
//        $pm = new MultilanguagePermalinkManager($locale['locale']);
//        $content_link = $pm->link(CONTENT_ID, 'content');
//
//
//        if ($currentLang == $locale['locale']) {
//            $locale['locale'] = 'x-default';
//        }
//
//        $locale['locale'] = str_replace('_', '-', $locale['locale']);
//
//        $link .= '<link rel="alternate" href="' . $content_link . '" hreflang="' . $locale['locale'] . '" />' . "\n";
//    }
//
//    return $link;
//});


/*event_bind('app.permalink.structure_map_prefix', function () {
    return 'locale';
});*/

//event_bind('app.category.get_category_id_from_url', function ($slug) {
//
//    Debugbar::addMessage('app.category.get_category_id_from_url', '1');
//
//    $relId = get_rel_id_by_multilanguage_url($slug, 'categories');
//
//    if ($relId) {
//        return $relId;
//    }
//
//    return false;
//});
//
//event_bind('app.permalink.link.after', function(){
//
//    Debugbar::addMessage('app.permalink.link.after', '1');
//    Debugbar::startMeasure('app.permalink.link.after','app.permalink.link.after');
//
//    $rewriteUrl = false;
//    $defaultLang = get_option('language', 'website');
//    $currentLang = mw()->lang_helper->current_lang();
//
//    $prefixForAll = get_option('add_prefix_for_all_languages','multilanguage_settings');
//
//    if ($defaultLang !== $currentLang) {
//        $rewriteUrl = true;
//    }
//
//    if ($prefixForAll == 'y') {
//        $rewriteUrl = true;
//    }
//
//    if ($rewriteUrl) {
//        // display locale
//        $localeSettings = db_get('multilanguage_supported_locales', 'locale=' . $currentLang . '&single=1');
//        if ($localeSettings && !empty($localeSettings['display_locale'])) {
//            $currentLang = $localeSettings['display_locale'];
//        }
//    }
//
//    Debugbar::stopMeasure('app.permalink.link.after');
//
//    if ($rewriteUrl) {
//        return $currentLang;
//    }
//
//});
//
//event_bind('app.permalink.slug.before', function ($params) {
//
//    Debugbar::addMessage('app.permalink.slug.before', '1');
//    Debugbar::startMeasure('app.permalink.slug.before','app.permalink.slug.before');
//
//    $relType = 'content';
//    if ($params['type'] == 'category') {
//        $relType = 'categories';
//    }
//
//    if ($relType == 'post' or $relType == 'page' or $relType == 'product') {
//        $relType = 'content';
//    }
//
//    $get = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url',$params['slug'],$relType);
//
//    if ($get) {
//
//        if ($relType == 'categories') {
//            $category = get_categories('id=' . $get['rel_id'] . '&single=1');
//            if ($category) {
//
//                Debugbar::stopMeasure('app.permalink.slug.before');
//
//                return $category['url'];
//            }
//        } else if ($relType == 'content') {
//            $content = get_content('id=' . $get['rel_id'] . '&single=1');
//            if ($content) {
//
//                Debugbar::stopMeasure('app.permalink.slug.before');
//
//                return $content['url'];
//            }
//        }
//    }
//
//    if ($relType == 'categories') {
//        $category = get_categories('url=' . $params['slug'] . '&single=1');
//        if ($category) {
//
//            Debugbar::stopMeasure('app.permalink.slug.before');
//
//            return $category['url'];
//        }
//    }
//
//    if ($relType == 'content') {
//        $content = get_content('url=' . $params['slug'] . '&single=1');
//        if ($content) {
//
//            Debugbar::stopMeasure('app.permalink.slug.before');
//
//            return $content['url'];
//        }
//    }
//
//    return false;
//});
//
//
///*event_bind('menu.after.get_item', function ($menu) {
//
//    if (isset($menu['url']) && !empty($menu['url']) && $menu['url'] !== site_url()) {
//
//        $default_lang = get_option('language', 'website');
//        $current_lang = mw()->lang_helper->current_lang();
//
//        if ($default_lang !== $current_lang) {
//            $new_url = str_replace(site_url(), site_url() . $current_lang . '/', $menu['url']);
//            $menu['url'] = $new_url;
//        }
//    }
//
//    return $menu;
//
//});*/
//
//
//event_bind('mw.controller.index', function ($content) {
//
//    Debugbar::startMeasure('mw.controller.index','mw.controller.index');
//
//    $autodetected_lang = \Cookie::get('autodetected_lang');
//    $lang_is_set = \Cookie::get('lang');
//
//    if ($autodetected_lang and $lang_is_set) {
//        return;
//    }
//
//    $targetUrl = mw()->url_manager->string();
//
//
//    $detect = detect_lang_from_url($targetUrl);
//
//
//    $useGeolocation = get_option('use_geolocation', 'multilanguage_settings');
//    if ($useGeolocation && $useGeolocation == 'y') {
////        if (!isset($_COOKIE['autodetected_lang']) and !isset($_COOKIE['lang'])) {
////            $geoLocation = get_geolocation();
////
////            if ($geoLocation && isset($geoLocation['countryCode'])) {
////                $language = get_country_language_by_country_code($geoLocation['countryCode']);
////
////               // var_dump($geoLocation);
////
////                if ($language && is_lang_supported($language)) {
////                    change_language_by_locale($language);
////                    setcookie('autodetected_lang', 1);
////                    return;
////                }
////            }
////
////        }
//    }
//
//
//    if (!is_lang_supported($detect['target_lang'])) {
//        Debugbar::stopMeasure('mw.controller.index');
//        return;
//    }
//
//
//    if ($detect['target_lang']) {
//        // display locale
//        $localeSettings = db_get('multilanguage_supported_locales', 'display_locale=' . $detect['target_lang'] . '&single=1');
//        if ($localeSettings) {
//            change_language_by_locale($localeSettings['locale']);
//        } else {
//            change_language_by_locale($detect['target_lang']);
//        }
//    }
//    Debugbar::stopMeasure('mw.controller.index');
//
//});
//
//event_bind('mw.front.content_data', function ($content) {
//
//    Debugbar::startMeasure('mw.front.content_data','mw.front.content_data');
//
//    if (isset($content['id']) and $content['id']) {
//        $redirect = mw_var('should_redirect');
//        if ($redirect) {
//            $content['original_link'] = $redirect;
//        }
//
//        Debugbar::stopMeasure('mw.front.content_data');
//
//        return $content;
//    }
//});
//
///*
//event_bind('mw.frontend.404', function ($content) {
//    if (isset($content['url'])) {
//        $content = get_content('url=' . $content['url'] . '&single=1');
//        if ($content and isset($content['id'])) {
//            $link = content_link($content['id']);
//            if ($link) {
//                $content['original_link'] = $link;
//                mw_var('should_redirect', $link);
//            }
//            return $content;
//        }
//    }
//});*/
//
//event_bind('app.content.get_by_url', function ($url) {
//
//    Debugbar::startMeasure('app.content.get_by_url','app.content.get_by_url');
//
//    if (!empty($url)) {
//
//        $detect = detect_lang_from_url($url);
//        $targetUrl = $detect['target_url'];
//        $targetLang = $detect['target_lang'];
//
//        if (empty($targetUrl)) {
//            $homepageGet = mw()->content_manager->homepage();
//            if ($homepageGet) {
//                mw_var('should_redirect', site_url() . $targetLang . '/' . $homepageGet['url']);
//                Debugbar::stopMeasure('app.content.get_by_url');
//                return;
//            }
//        }
//
//        if (!$targetUrl || !$targetLang) {
//            Debugbar::stopMeasure('app.content.get_by_url');
//            return;
//        }
//
//        $targetUrl = urldecode($targetUrl);
//
//        $findTranslate = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url',$targetUrl,'content');
//
//        if ($findTranslate && intval($findTranslate['rel_id']) !== 0) {
//
//            $get = array();
//            $get['id'] = $findTranslate['rel_id'];
//            $get['single'] = true;
//            $content = mw()->content_manager->get($get);
//
//            if (!$content) {
//                Debugbar::stopMeasure('app.content.get_by_url');
//                return;
//            }
//
//            if ($content['url'] == $findTranslate['field_value']) {
//                Debugbar::stopMeasure('app.content.get_by_url');
//                return $content;
//            } else {
//                /**
//                 * When you visit url with prefix with diffrent language it redirects you to url with correct lang
//                 * Example /bg-lang/english-post-name > /bg-lang/imeto-na-posta-na-bg
//                 */
//                mw_var('should_redirect', content_link($content['id']));
//                Debugbar::stopMeasure('app.content.get_by_url');
//                return $content;
//            }
//        } else {
//            $get = array();
//            $get['url'] = $targetUrl;
//            $get['single'] = true;
//
//            $content = mw()->content_manager->get($get);
//            if (!$content) {
//                Debugbar::stopMeasure('app.content.get_by_url');
//                return;
//            }
//            if ($content) {
//                if ($content['url'] !== $targetUrl) {
//                    /**
//                     * When you visit url with prefix with diffrent language it redirects you to url with correct lang
//                     * Example /bg-lang/english-post-name > /bg-lang/imeto-na-posta-na-bg
//                     */
//                    mw_var('should_redirect', content_link($content['id']));
//                    Debugbar::stopMeasure('app.content.get_by_url');
//                    return $content;
//                }
//                Debugbar::stopMeasure('app.content.get_by_url');
//                return $content;
//            }
//        }
//    }
//
//    return;
//});
//
//
//event_bind('app.category.get_by_url', function ($url) {
//
//    Debugbar::startMeasure('app.category.get_by_url','app.category.get_by_url');
//
//    if (!empty($url)) {
//
//        $detect = detect_lang_from_url($url);
//        $targetUrl = $detect['target_url'];
//        $targetLang = $detect['target_lang'];
//
//        if (empty($targetUrl)) {
//            $homepageGet = mw()->content_manager->homepage();
//            if ($homepageGet) {
//                //mw_var('should_redirect', site_url() . $targetLang . '/' . $homepageGet['url']);
//                Debugbar::stopMeasure('app.category.get_by_url');
//                return;
//            }
//        }
//
//        if (!$targetUrl || !$targetLang) {
//            Debugbar::stopMeasure('app.category.get_by_url');
//            return;
//        }
//
//        $targetUrl = urldecode($targetUrl);
//
//        $findTranslate = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url',$targetUrl,'categories');
//
//        if ($findTranslate && intval($findTranslate['rel_id']) !== 0) {
//
//            $get = array();
//            $get['id'] = $findTranslate['rel_id'];
//            $get['single'] = true;
//            $content = mw()->category_manager->get($get);
//
//            if (is_array($content) and is_array($findTranslate) and $content['url'] == $findTranslate['field_value']) {
//                Debugbar::stopMeasure('app.category.get_by_url');
//                return $content;
//            } else {
//                /**
//                 * When you visit url with prefix with diffrent language it redirects you to url with correct lang
//                 * Example /bg-lang/english-post-name > /bg-lang/imeto-na-posta-na-bg
//                 */
//                if (is_array($content) and isset($content['id'])) {
//                    mw_var('should_redirect', category_link($content['id']));
//                    Debugbar::stopMeasure('app.category.get_by_url');
//                    return $content;
//                }
//
//            }
//        } else {
//            $get = array();
//            $get['url'] = $targetUrl;
//            $get['single'] = true;
//
//            $content = mw()->category_manager->get($get);
//            if ($content) {
//                if ($content['url'] !== $targetUrl) {
//                    /**
//                     * When you visit url with prefix with diffrent language it redirects you to url with correct lang
//                     * Example /bg-lang/english-post-name > /bg-lang/imeto-na-posta-na-bg
//                     */
//                    mw_var('should_redirect', category_link($content['id']));
//                    Debugbar::stopMeasure('app.category.get_by_url');
//                    return $content;
//                }
//                Debugbar::stopMeasure('app.category.get_by_url');
//                return $content;
//            }
//        }
//    }
//
//    Debugbar::stopMeasure('app.category.get_by_url');
//    return;
//});

