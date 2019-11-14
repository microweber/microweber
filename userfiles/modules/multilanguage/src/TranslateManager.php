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
        $currentLocale = mw()->lang_helper->current_lang();
        $defaultLocale = mw()->lang_helper->default_lang();

        if (!empty($this->translateProviders)) {
            foreach ($this->translateProviders as $provider) {

                $providerInstance = new $provider();
                $providerTable = $providerInstance->getRelType();

                // BIND GET TABLES
                event_bind('mw.database.'.$providerTable.'.get', function($get) use ($currentLocale, $defaultLocale, $providerInstance) {
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