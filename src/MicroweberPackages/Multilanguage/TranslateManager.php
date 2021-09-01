<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace MicroweberPackages\Multilanguage;

require_once __DIR__ . '/TranslateTable.php';
require_once __DIR__ . '/TranslateTables/TranslateMenu.php';
require_once __DIR__ . '/TranslateTables/TranslateOption.php';
require_once __DIR__ . '/TranslateTables/TranslateCategory.php';
require_once __DIR__ . '/TranslateTables/TranslateContent.php';
require_once __DIR__ . '/TranslateTables/TranslateContentFields.php';
require_once __DIR__ . '/TranslateTables/TranslateCustomFields.php';
require_once __DIR__ . '/TranslateTables/TranslateTestimonials.php';
require_once __DIR__ . '/TranslateTables/TranslateTaggingTags.php';
require_once __DIR__ . '/TranslateTables/TranslateTaggingTagged.php';
require_once __DIR__ . '/TranslateTables/TranslateDynamicTextVariables.php';

class TranslateManager
{

    public $translateProviders = [
        'TranslateMenu',
        'TranslateOption',
        'TranslateCategory',
        'TranslateContent',
        // 'TranslateContentData',
        'TranslateContentFields',
        'TranslateCustomFields',
        'TranslateTestimonials',
        'TranslateTaggingTags',
        'TranslateTaggingTagged',
        'TranslateDynamicTextVariables'
    ];

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

        if (!empty($this->translateProviders)) {
            foreach ($this->translateProviders as $provider) {

                $providerInstance = new $provider();
                $providerTable = $providerInstance->getRelType();

                if ($providerInstance->getRepositoryMethods()) {
                    foreach ($providerInstance->getRepositoryMethods() as $repositoryMethod) {
                        event_bind($providerInstance->getRepositoryClass() . '\\' . $repositoryMethod, function ($data) use ($providerInstance) {
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

                event_bind('mw.database.' . $providerTable . '.save.after', function ($saveData) use ($providerInstance,$currentLocale,$defaultLocale) {

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
