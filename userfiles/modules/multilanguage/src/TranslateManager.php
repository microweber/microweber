<?php
require_once __DIR__ . '/TranslateTable.php';
require_once __DIR__ . '/TranslateTables/TranslateMenu.php';
require_once __DIR__ . '/TranslateTables/TranslateOption.php';
require_once __DIR__ . '/TranslateTables/TranslateCategory.php';
require_once __DIR__ . '/TranslateTables/TranslateContent.php';
require_once __DIR__ . '/TranslateTables/TranslateContentFields.php';
require_once __DIR__ . '/TranslateTables/TranslateTestimonials.php';

class TranslateManager
{

    public $translateProviders = [
        'TranslateMenu',
        'TranslateOption',
        'TranslateCategory',
        'TranslateContent',
        'TranslateContentFields',
        'TranslateTestimonials'
    ];


    public function run()
    {


        //if ($currentLocale != $defaultLocale) {
        event_bind('content.get_by_url.not_found', function ($params)  {

            if (isset($params['url'])) {

                $filter = array();
                $filter['single'] = 1;
                $filter['rel_type'] = 'content';
                $filter['field_name'] = 'url';
                $filter['field_value'] = $params['url'];

                $findTranslate = db_get('translations', $filter);

                if ($findTranslate) {
                    $new_params = array();
                    $new_params['id'] = $findTranslate['rel_id'];
                    $new_params['single'] = 1;

                    mw()->lang_helper->set_current_lang($findTranslate['locale']);

                    return $new_params;

                }


            }
        });

        $currentLocale = mw()->lang_helper->current_lang();
        $defaultLocale = mw()->lang_helper->default_lang();

        if (!empty($this->translateProviders)) {
            foreach ($this->translateProviders as $provider) {

                $providerInstance = new $provider();
                $providerTable = $providerInstance->getRelType();

                // BIND GET TABLES
                event_bind('mw.database.' . $providerTable . '.get', function ($get) use ($currentLocale, $defaultLocale, $providerInstance) {
                    if (is_array($get) && !empty($get)) {
                        foreach ($get as &$item) {
                            if (isset($item['option_key']) && $item['option_key'] == 'language') {
                                continue;
                            }
                            $item = $providerInstance->getTranslate($item);
                        }
                    }
                    return $get;
                });

                // BIND SAVE TABLES
                event_bind('mw.database.' . $providerTable . '.save.params', function ($saveData) use ($currentLocale, $defaultLocale, $providerInstance) {
                    if ($currentLocale != $defaultLocale) {

                        if (isset($saveData['option_key']) && $saveData['option_key'] == 'language') {
                            return false;
                        }

                        if (!empty($providerInstance->getColumns())) {
                            $dataForTranslate = $saveData;
                            foreach ($providerInstance->getColumns() as $column) {

                                if (!isset($saveData['id'])) {
                                    continue;
                                }

                                if (isset($saveData[$column])) {
                                    unset($saveData[$column]);
                                }
                            }

                            if (!empty($dataForTranslate)) {
                                $providerInstance->saveOrUpdate($dataForTranslate);
                            }
                        }
                    }

                    return $saveData;

                });

            }
        }

    }

}