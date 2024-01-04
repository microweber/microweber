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

                    if (!MultilanguageHelpers::multilanguageIsEnabled()) {
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

                    if (!MultilanguageHelpers::multilanguageIsEnabled()) {
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

                    if (!MultilanguageHelpers::multilanguageIsEnabled()) {
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
                            $skip = false;
                            if(isset($saveData['field']) and $saveData['field']) {
                                $is_native_fld_all = app()->database_manager->get_fields('content');
                                if (in_array($saveData['field'], $is_native_fld_all)) {
                                    //return $saveData;
                                    $skip = true;
                                }
                            }
                            if (!$skip) {
                                $saveData['__value'] = $saveData['value'];
                                unset($saveData['value']);
                                return $saveData;
                            }
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

                    if (!MultilanguageHelpers::multilanguageIsEnabled()) {
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


        event_bind('app.content.get_by_url', function ($url) {

            // Debugbar::startMeasure('app.content.get_by_url','app.content.get_by_url');

            if (!empty($url)) {

                $detect = detect_lang_from_url($url);
                $targetLang = $detect['target_lang'];
                if (!$targetLang) {
                    // Debugbar::stopMeasure('app.content.get_by_url');
                    return;
                }

                $targetUrl = urldecode($url);
                $findTranslate = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url', $targetUrl, 'content');

                if ($findTranslate && intval($findTranslate['rel_id']) !== 0) {

                    $get = array();
                    $get['id'] = $findTranslate['rel_id'];
                    $get['single'] = true;
                    $content = app()->content_manager->get($get);

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

                    $content = app()->content_manager->get($get);
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
                $targetLang = $detect['target_lang'];
                if (!$targetLang) {
                    // Debugbar::stopMeasure('app.category.get_by_url');
                    return;
                }

                $targetUrl = urldecode($url);

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
