<?php
require_once __DIR__ . '/TranslateTable.php';
require_once __DIR__ . '/TranslateTables/TranslateMenu.php';
require_once __DIR__ . '/TranslateTables/TranslateOption.php';
require_once __DIR__ . '/TranslateTables/TranslateCategory.php';
require_once __DIR__ . '/TranslateTables/TranslateContent.php';
require_once __DIR__ . '/TranslateTables/TranslateContentFields.php';

class TranslateManager {

    public $translateProviders = [
        'TranslateMenu',
        'TranslateOption',
        'TranslateCategory',
        'TranslateContent',
        'TranslateContentFields'
    ];

    public function run() {

        if (!empty($this->translateProviders)) {
            foreach ($this->translateProviders as $provider) {

                $providerInstance = new $provider();
                $providerTable = $providerInstance->getRelType();

                // BIND GET TABLES
                event_bind('mw.database.'.$providerTable.'.get', function($get) use ($providerInstance) {
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
                event_bind('mw.database.'.$providerTable.'.save.params', function($save) use ($providerInstance) {

                    $currentLocale = mw()->lang_helper->current_lang();
                    $defaultLocale = mw()->lang_helper->default_lang();

                    if ($currentLocale != $defaultLocale) {

                        if (isset($save['option_key']) && $save['option_key'] == 'language') {
                            return false;
                        }

                        if (isset($save['id']) && isset($save['option_value'])) {
                            $providerInstance->saveOrUpdate($save);
                        }

                        if (isset($save['option_value'])) {
                            unset($save['option_value']);
                        }
                    }

                    return $save;
                });

            }
        }

    }

}