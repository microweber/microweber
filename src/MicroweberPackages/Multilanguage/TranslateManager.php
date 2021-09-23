<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */

namespace MicroweberPackages\Multilanguage;

use MicroweberPackages\Multilanguage\TranslateTables\TranslateCategory;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateContent;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateContentFields;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateCustomFields;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateDynamicTextVariables;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateMenu;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateOption;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateTaggingTagged;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateTaggingTags;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateTestimonials;

class TranslateManager
{

    public static $translateProviders = [];

    public function addTranslateProvider($providerClass)
    {
        self::$translateProviders[] = $providerClass;
        return $this;
    }

    public function run()
    {
        // BIND GET TABLES
        $currentLocale = $this->getCurrentLocale();
        $defaultLocale = $this->getDefaultLocale();

        $translatableModuleOptions = [];
        foreach (get_modules_from_db() as $module) {
            if (isset($module['settings']['translatable_options'])) {
                $translatableModuleOptions[$module['module']] = $module['settings']['translatable_options'];
            }
        }

        if (!empty(self::$translateProviders)) {
            foreach (self::$translateProviders as $provider) {

                $providerInstance = new $provider();
                $providerTable = $providerInstance->getRelType();

                if ($providerInstance->getRepositoryMethods()) {
                    foreach ($providerInstance->getRepositoryMethods() as $repositoryMethod) {

                        //   dump($providerInstance->getRepositoryClass() . '\\' . $repositoryMethod);

                        event_bind($providerInstance->getRepositoryClass() . '\\' . $repositoryMethod, function ($data) use ($providerInstance) {
/*
                            if (isset($data['getEditField'])) {
                                dump($data);
                            }*/

                            if (isset($data['data']) && !empty($data['data']) && isset($data['hook_overwrite_type'])) {
                                if ($data['hook_overwrite_type'] == 'multiple') {

                                    foreach ($data['data'] as &$item) {
                                        $translate = $providerInstance->getTranslate($item);
                                        if (!empty($translate)) {
                                            $item = $translate;
                                        }
                                    }

                                } else if ($data['hook_overwrite_type'] == 'single') {
                                    $translate = $providerInstance->getTranslate($data['data']);
                                    if (!empty($translate)) {
                                        $data['data'] = $translate;
                                    }
                                }
                            }
                            return $data;
                        });
                    }
                }

                event_bind('mw.database.' . $providerTable . '.get.query_filter', function ($params) use ($providerTable, $providerInstance) {

                    if (defined('MW_DISABLE_MULTILANGUAGE')) {
                        return;
                    }

                    if (isset($params['params']['data-keyword'])) {

                        $keyword = $params['params']['data-keyword'];
                        // $searchInFields = $params['params']['search_in_fields'];
                        $params['query']->where(function ($query) use ($params, $providerTable, $keyword) {

                            $query->orWhereIn($providerTable . '.id', function ($subQuery) use ($providerTable, $keyword) {
                                $subQuery->select('multilanguage_translations.rel_id');
                                $subQuery->from('multilanguage_translations');
                                $subQuery->where('multilanguage_translations.rel_type', '=', $providerTable);
                                /*foreach ($searchInFields as $field) {
                                     $subQuery->orWhere(function($query) use ($field, $keyword) {
                                         $query->where('field_name', $field);
                                         $query->where('field_value', 'LIKE', '%'.$keyword.'%');
                                     });
                                 }*/
                                $subQuery->where('multilanguage_translations.field_value', 'LIKE', '%' . $keyword . '%');
                            });

                        });

                    }

                    /*     if (isset($params['params']['url'])) {
                              $url = $params['params']['url'];
                              if ($providerTable =='categories') {

                                 var_dump($url);

                                  $params['query']->whereIn($providerTable.'.id', function ($subQuery) use ($providerTable, $url) {
                                      $subQuery->select('multilanguage_translations.rel_id');
                                      $subQuery->from('multilanguage_translations');
                                      $subQuery->where('multilanguage_translations.id', '2348');
                                      //$subQuery->where('multilanguage_translations.field_name', 'url');
                                     // $subQuery->where('multilanguage_translations.rel_type', '=', $providerTable);
                                     // $subQuery->where('multilanguage_translations.field_value', $url);
                                  });

                                  dd($params['query']);
                              }
                          }*/

                    return $params;
                });

                event_bind('mw.database.' . $providerTable . '.get', function ($get) use ($providerTable, $providerInstance, $translatableModuleOptions) {

                    if (defined('MW_DISABLE_MULTILANGUAGE')) {
                        return;
                    }
                    $cache_exp = 3600;
                    if (is_array($get) && !empty($get)) {


                        //  $getHash = md5(serialize($get) . '_' . $currentLocale);

//                        $getHash = $providerTable . crc32(json_encode($get) . '_' . $currentLocale);
//
//
//                        $cacheGet = cache_get($getHash, 'global',$cache_exp);
//                        if ($cacheGet && is_array($cacheGet) && !empty($cacheGet)) {
//                            return $cacheGet;
//                        }

                        foreach ($get as &$item) {

                            if ($providerTable == 'options') {
                                $saveModuleOption = false;
                                if (isset($item['module']) && isset($item['option_key'])) {
                                    if (isset($translatableModuleOptions[$item['module']]) && in_array($item['option_key'], $translatableModuleOptions[$item['module']])) {
                                        $saveModuleOption = true;
                                    }
                                }
                                if ($saveModuleOption == false) {
                                    continue;
                                }
                            }

                            $item = $providerInstance->getTranslate($item);
                        }

                        //     cache_save($get, $getHash, 'global', $cache_exp);
                    }
                    return $get;
                });

                // BIND SAVE TABLES
                event_bind('mw.database.' . $providerTable . '.save.params', function ($saveData) use ($providerTable, $currentLocale, $defaultLocale, $providerInstance, $translatableModuleOptions) {

                    if (defined('MW_DISABLE_MULTILANGUAGE')) {
                        return;
                    }

                    if ($providerTable == 'options') {
                        $saveModuleOption = false;
                        if (isset($saveData['module']) && isset($saveData['option_key'])) {
                            if (isset($translatableModuleOptions[$saveData['module']]) && in_array($saveData['option_key'], $translatableModuleOptions[$saveData['module']])) {
                                $saveModuleOption = true;
                            }
                        }
                        if ($saveModuleOption == false) {
                            return false;
                        }
                    }

                    if ($currentLocale != $defaultLocale) {
                        if ($providerInstance->getRelType() == 'options') {
                            $saveData['__option_value'] = $saveData['option_value'];
                            unset($saveData['option_value']);
                            return $saveData;
                        }

                        if ($providerInstance->getRelType() == 'content_fields') {
                            $saveData['__value'] = $saveData['value'];
                            unset($saveData['value']);
                            return $saveData;
                        }
                    }

                    if (!empty($providerInstance->getColumns())) {
                        $dataForTranslate = $saveData;
                        foreach ($providerInstance->getColumns() as $column) {

                            if (!isset($saveData['id'])) {
                                continue;
                            }

                            if (intval($saveData['id']) !== 0) {
                                if ($currentLocale != $defaultLocale) {
                                    if (isset($saveData[$column])) {
                                        unset($saveData[$column]);
                                    }
                                }
                            }
                        }

                        if ($currentLocale != $defaultLocale) {
                            if (!empty($dataForTranslate) && isset($dataForTranslate['id']) && intval($dataForTranslate['id']) !== 0) {
                                $providerInstance->saveOrUpdate($dataForTranslate);
                            }
                        }
                    }

                    return $saveData;
                });

                event_bind('mw.database.' . $providerTable . '.save.after', function ($saveData) use ($providerInstance, $currentLocale, $defaultLocale) {

                    if (defined('MW_DISABLE_MULTILANGUAGE')) {
                        return;
                    }

                    if ($currentLocale != $defaultLocale) {
                        if (!empty($providerInstance->getColumns())) {

                            if ($providerInstance->getRelType() == 'content_fields' && isset($saveData['__value'])) {
                                $saveData['value'] = $saveData['__value'];
                                unset($saveData['__value']);
                                $providerInstance->saveOrUpdate($saveData);
                            }

                            if ($providerInstance->getRelType() == 'options' && isset($saveData['__option_value'])) {
                                $saveData['option_value'] = $saveData['__option_value'];
                                unset($saveData['__option_value']);
                                $providerInstance->saveOrUpdate($saveData);
                            }


                            cache_clear('multilanguage');
                        }
                    }

                });


            }
        }


        // events for links


        event_bind('app.permalink.link.after', function () {

            // Debugbar::addMessage('app.permalink.link.after', '1');
            // Debugbar::startMeasure('app.permalink.link.after','app.permalink.link.after');

            $rewriteUrl = false;
            $defaultLang = get_option('language', 'website');
            $currentLang = mw()->lang_helper->current_lang();

            $prefixForAll = get_option('add_prefix_for_all_languages', 'multilanguage_settings');

            if ($defaultLang !== $currentLang) {
                $rewriteUrl = true;
            }

            if ($prefixForAll == 'y') {
                $rewriteUrl = true;
            }

            if ($rewriteUrl) {
                // display locale
                $localeSettings = db_get('multilanguage_supported_locales', 'locale=' . $currentLang . '&single=1');
                if ($localeSettings && !empty($localeSettings['display_locale'])) {
                    $currentLang = $localeSettings['display_locale'];
                }
            }

            // Debugbar::stopMeasure('app.permalink.link.after');

            if ($rewriteUrl) {
                return $currentLang;
            }

        });

        event_bind('app.permalink.slug.before', function ($params) {

            // Debugbar::addMessage('app.permalink.slug.before', '1');
            // Debugbar::startMeasure('app.permalink.slug.before','app.permalink.slug.before');

            $relType = 'content';
            if ($params['type'] == 'category') {
                $relType = 'categories';
            }

            if ($relType == 'post' or $relType == 'page' or $relType == 'product') {
                $relType = 'content';
            }

            $get = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url', $params['slug'], $relType);

            if ($get) {

                if ($relType == 'categories') {
                    $category = get_categories('id=' . $get['rel_id'] . '&single=1');
                    if ($category) {

                        // Debugbar::stopMeasure('app.permalink.slug.before');

                        return $category['url'];
                    }
                } else if ($relType == 'content') {
                    $content = get_content('id=' . $get['rel_id'] . '&single=1');
                    if ($content) {

                        // Debugbar::stopMeasure('app.permalink.slug.before');

                        return $content['url'];
                    }
                }
            }

            if ($relType == 'categories') {
                $category = get_categories('url=' . $params['slug'] . '&single=1');
                if ($category) {

                    // Debugbar::stopMeasure('app.permalink.slug.before');

                    return $category['url'];
                }
            }

            if ($relType == 'content') {
                $content = get_content('url=' . $params['slug'] . '&single=1');
                if ($content) {

                    // Debugbar::stopMeasure('app.permalink.slug.before');

                    return $content['url'];
                }
            }

            return false;
        });


        /*event_bind('menu.after.get_item', function ($menu) {

            if (isset($menu['url']) && !empty($menu['url']) && $menu['url'] !== site_url()) {

                $default_lang = get_option('language', 'website');
                $current_lang = mw()->lang_helper->current_lang();

                if ($default_lang !== $current_lang) {
                    $new_url = str_replace(site_url(), site_url() . $current_lang . '/', $menu['url']);
                    $menu['url'] = $new_url;
                }
            }

            return $menu;

        });*/


        event_bind('mw.controller.index', function ($content) {

            // Debugbar::startMeasure('mw.controller.index','mw.controller.index');

            $autodetected_lang = \Cookie::get('autodetected_lang');
            $lang_is_set = \Cookie::get('lang');

            if ($autodetected_lang and $lang_is_set) {
                return;
            }

            $targetUrl = mw()->url_manager->string();


            $detect = detect_lang_from_url($targetUrl);


            $useGeolocation = get_option('use_geolocation', 'multilanguage_settings');
            if ($useGeolocation && $useGeolocation == 'y') {
//        if (!isset($_COOKIE['autodetected_lang']) and !isset($_COOKIE['lang'])) {
//            $geoLocation = get_geolocation();
//
//            if ($geoLocation && isset($geoLocation['countryCode'])) {
//                $language = get_country_language_by_country_code($geoLocation['countryCode']);
//
//               // var_dump($geoLocation);
//
//                if ($language && is_lang_supported($language)) {
//                    change_language_by_locale($language);
//                    setcookie('autodetected_lang', 1);
//                    return;
//                }
//            }
//
//        }
            }


            if (!is_lang_supported($detect['target_lang'])) {
                // Debugbar::stopMeasure('mw.controller.index');
                return;
            }


            if (!$autodetected_lang and !$lang_is_set) {
                $homepageLanguage = get_option('homepage_language', 'website');
                if ($homepageLanguage) {
                    if (is_lang_supported($homepageLanguage)) {
                        change_language_by_locale($homepageLanguage);
                        \Cookie::queue('autodetected_lang', 1, 600);
                        return;
                    }
                }
            }

            if ($detect['target_lang']) {
                if (!$lang_is_set or ($lang_is_set and $lang_is_set != $detect['target_lang'])) {

                    $localeSettings = db_get('multilanguage_supported_locales', 'display_locale=' . $detect['target_lang'] . '&single=1');
                    if ($localeSettings) {

                        change_language_by_locale($localeSettings['locale']);
                    } else {
                        change_language_by_locale($detect['target_lang']);
                    }
                }

            }
            // Debugbar::stopMeasure('mw.controller.index');

        });

        event_bind('mw.front.content_data', function ($content) {

            // Debugbar::startMeasure('mw.front.content_data','mw.front.content_data');

            if (isset($content['id']) and $content['id']) {
                $redirect = mw_var('should_redirect');
                if ($redirect) {
                  //  $content['original_link'] = $redirect;
                }

                // Debugbar::stopMeasure('mw.front.content_data');

                return $content;
            }
        });

        /*
        event_bind('mw.frontend.404', function ($content) {
            if (isset($content['url'])) {
                $content = get_content('url=' . $content['url'] . '&single=1');
                if ($content and isset($content['id'])) {
                    $link = content_link($content['id']);
                    if ($link) {
                        $content['original_link'] = $link;
                        mw_var('should_redirect', $link);
                    }
                    return $content;
                }
            }
        });*/

        event_bind('app.content.get_by_url', function ($url) {

            // Debugbar::startMeasure('app.content.get_by_url','app.content.get_by_url');

            if (!empty($url)) {

                $detect = detect_lang_from_url($url);
                $targetUrl = $detect['target_url'];
                $targetLang = $detect['target_lang'];

                if (empty($targetUrl)) {
                    $homepageGet = mw()->content_manager->homepage();
                    if ($homepageGet) {
                        mw_var('should_redirect', site_url() . $targetLang . '/' . $homepageGet['url']);
                        // Debugbar::stopMeasure('app.content.get_by_url');
                        return;
                    }
                }

                if (!$targetUrl || !$targetLang) {
                    // Debugbar::stopMeasure('app.content.get_by_url');
                    return;
                }

                $targetUrl = urldecode($targetUrl);

                $findTranslate = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url', $targetUrl, 'content');

                if ($findTranslate && intval($findTranslate['rel_id']) !== 0) {

                    $get = array();
                    $get['id'] = $findTranslate['rel_id'];
                    $get['single'] = true;
                    $content = mw()->content_manager->get($get);

                    if (!$content) {
                        // Debugbar::stopMeasure('app.content.get_by_url');
                        return;
                    }

                    if ($content['url'] == $findTranslate['field_value']) {
                        // Debugbar::stopMeasure('app.content.get_by_url');
                        return $content;
                    } else {
                        /**
                         * When you visit url with prefix with diffrent language it redirects you to url with correct lang
                         * Example /bg-lang/english-post-name > /bg-lang/imeto-na-posta-na-bg
                         */
                        mw_var('should_redirect', content_link($content['id']));
                        // Debugbar::stopMeasure('app.content.get_by_url');
                        return $content;
                    }
                } else {
                    $get = array();
                    $get['url'] = $targetUrl;
                    $get['single'] = true;

                    $content = mw()->content_manager->get($get);
                    if (!$content) {
                        // Debugbar::stopMeasure('app.content.get_by_url');
                        return;
                    }
                    if ($content) {
                        if ($content['url'] !== $targetUrl) {
                            /**
                             * When you visit url with prefix with diffrent language it redirects you to url with correct lang
                             * Example /bg-lang/english-post-name > /bg-lang/imeto-na-posta-na-bg
                             */
                            mw_var('should_redirect', content_link($content['id']));
                            // Debugbar::stopMeasure('app.content.get_by_url');
                            return $content;
                        }
                        // Debugbar::stopMeasure('app.content.get_by_url');
                        return $content;
                    }
                }
            }

            return;
        });


        event_bind('app.category.get_by_url', function ($url) {

            // Debugbar::startMeasure('app.category.get_by_url','app.category.get_by_url');

            if (!empty($url)) {

                $detect = detect_lang_from_url($url);
                $targetUrl = $detect['target_url'];
                $targetLang = $detect['target_lang'];

                if (empty($targetUrl)) {
                    $homepageGet = mw()->content_manager->homepage();
                    if ($homepageGet) {
                        //mw_var('should_redirect', site_url() . $targetLang . '/' . $homepageGet['url']);
                        // Debugbar::stopMeasure('app.category.get_by_url');
                        return;
                    }
                }

                if (!$targetUrl || !$targetLang) {
                    // Debugbar::stopMeasure('app.category.get_by_url');
                    return;
                }

                $targetUrl = urldecode($targetUrl);

                $findTranslate = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url', $targetUrl, 'categories');

                if ($findTranslate && intval($findTranslate['rel_id']) !== 0) {

                    $get = array();
                    $get['id'] = $findTranslate['rel_id'];
                    $get['single'] = true;
                    $content = mw()->category_manager->get($get);

                    if (is_array($content) and is_array($findTranslate) and $content['url'] == $findTranslate['field_value']) {
                        // Debugbar::stopMeasure('app.category.get_by_url');
                        return $content;
                    } else {
                        /**
                         * When you visit url with prefix with diffrent language it redirects you to url with correct lang
                         * Example /bg-lang/english-post-name > /bg-lang/imeto-na-posta-na-bg
                         */
                        if (is_array($content) and isset($content['id'])) {
                            mw_var('should_redirect', category_link($content['id']));
                            // Debugbar::stopMeasure('app.category.get_by_url');
                            return $content;
                        }

                    }
                } else {
                    $get = array();
                    $get['url'] = $targetUrl;
                    $get['single'] = true;

                    $content = mw()->category_manager->get($get);
                    if ($content) {
                        if ($content['url'] !== $targetUrl) {
                            /**
                             * When you visit url with prefix with diffrent language it redirects you to url with correct lang
                             * Example /bg-lang/english-post-name > /bg-lang/imeto-na-posta-na-bg
                             */
                            mw_var('should_redirect', category_link($content['id']));
                            // Debugbar::stopMeasure('app.category.get_by_url');
                            return $content;
                        }
                        // Debugbar::stopMeasure('app.category.get_by_url');
                        return $content;
                    }
                }
            }

            // Debugbar::stopMeasure('app.category.get_by_url');
            return;
        });


        event_bind('app.category.get_category_id_from_url', function ($slug) {

            //  Debugbar::addMessage('app.category.get_category_id_from_url', '1');

            $relId = get_rel_id_by_multilanguage_url($slug, 'categories');

            if ($relId) {
                return $relId;
            }

            return false;
        });


    }

    public static $_getCurrentLocale = false;

    public function getCurrentLocale()
    {
        if (self::$_getCurrentLocale) {
            return self::$_getCurrentLocale;
        }

        self::$_getCurrentLocale = app()->lang_helper->current_lang();

        return self::$_getCurrentLocale;
    }

    public static $_getDefaultLocale = false;

    public function getDefaultLocale()
    {
        if (self::$_getDefaultLocale) {
            return self::$_getDefaultLocale;
        }

        self::$_getDefaultLocale = app()->lang_helper->default_lang();

        return self::$_getDefaultLocale;
    }
}
